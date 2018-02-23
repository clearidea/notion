<?php

namespace Notion;

use Neuron\Data\StringData;
use Notion;

use \Neuron\Patterns\IRunnable;

/**
 * Class Router
 * @package Notion
 */

class Router implements IRunnable
{
	private $_Delete = [];
	private $_Get    = [];
	private $_Post   = [];
	private $_Put    = [];
	private $_Filter = [];

	private $_FilterRegistry = [];

	public function registerFilter( string $Name, Filter $Filter )
	{
		$this->_FilterRegistry[ $Name ] = $Filter;
	}

	public function getFilter( string $Name ) : Filter
	{
		$Filter = null;

		if( array_key_exists( $Name, $this->_FilterRegistry ) )
		{
			$Filter = $this->_FilterRegistry[ $Name ];
		}
		else
		{
			throw new \Exception( "Filter $Name not registered." );
		}
		return $Filter;
	}

	public function addFilter( string $Filter )
	{
		$this->_Filter[] = $Filter;
	}

	/**
	 * @param array $aRoutes
	 * @param $sRoute
	 * @param $function
	 * @param $Filter
	 * @throws \Exception
	 */
	protected function addRoute( array &$aRoutes, $sRoute, $function, $Filter )
	{
		$aRoutes[] = new Notion\Route( $sRoute, $function, $Filter );
	}

	/**
	 * @param $sRoute
	 * @param $function
	 * @return $this
	 * @param $Filter
	 * @throws \Exception
	 */
	public function delete( $sRoute, $function, $Filter = null )
	{
		$this->addRoute( $this->_Delete, $sRoute, $function, $Filter );
		return $this;
	}

	/**
	 * @param $sRoute
	 * @param $function
	 * @return $this
	 * @param $Filter
	 * @throws \Exception
	 */
	public function get( $sRoute, $function, $Filter = null )
	{
		$this->addRoute( $this->_Get, $sRoute, $function, $Filter );
		return $this;
	}

	/**
	 * @param $sRoute
	 * @param $function
	 * @return $this
	 * @param $Filter
	 * @throws \Exception
	 */
	public function post( $sRoute, $function, $Filter = null )
	{
		$this->addRoute( $this->_Post, $sRoute, $function, $Filter );
		return $this;
	}

	/**
	 * @param $sRoute
	 * @param $function
	 * @param $Filter
	 * @return $this
	 * @throws \Exception
	 */
	public function put( $sRoute, $function, $Filter = null )
	{
		$this->addRoute( $this->_Put, $sRoute, $function, $Filter );
		return $this;
	}

	/**
	 * @param Route $Route
	 * @return bool
	 */
	protected function isRouteWithParams( Route $Route )
	{
		return strpos( $Route->Path, ':' ) == true;
	}

	/**
	 * @param $Route
	 * @param $sUri
	 * @return array|bool
	 * @throws \Exception
	 */
	protected function processRoute( Route $Route, $sUri )
	{
		// Does route have parameters?

		if( $this->isRouteWithParams( $Route ) )
		{
			$Segments = count( explode( '/', $sUri ) );

			$RouteSegments = count( explode( '/', $Route->Path ) );

			if( $Segments == $RouteSegments )
			{
				return $this->processRouteWithParameters( $Route, $sUri );
			}
		}
		else
		{
			if( !$sUri )
			{
				$sUri = '/';
			}
			else if( $sUri[ 0 ] != '/' )
			{
				$sUri = '/' . $sUri;
			}

			if( $Route->Path == $sUri )
			{
				return true;
			}
		}

		return false;
	}

	/**
	 * @param Route $Route
	 * @param $sUri
	 * @return array
	 * @throws \Exception
	 */
	protected function processRouteWithParameters( Route $Route, $sUri )
	{
		$aDetails = $Route->parseParams();

		return $this->extractRouteParams( $sUri, $aDetails );
	}

	/**
	 * Populates a param array with the data from the uri.
	 * @param $sUri
	 * @param $aDetails
	 * @return array
	 */
	protected function extractRouteParams( $sUri, $aDetails )
	{
		if( $sUri && $sUri[ 0 ]  == '/' )
		{
			$String = new StringData( $sUri );
			$sUri   = $String->right( $String->length() - 1 );
		}

		$aUri = explode( '/', $sUri );

		$aParams = [];
		$iOffset = 0;

		foreach( $aUri as $sPart )
		{
			if( $iOffset >= count( $aDetails ) )
			{
				return [];
			}

			$action = $aDetails[ $iOffset ][ 'action' ];
			if( $action )
			{
				if( $action != $sPart )
				{
					return [];
				}
			}
			else
			{
				$aParams[ $aDetails[ $iOffset ][ 'param' ] ] = $sPart;
			}

			$iOffset++;
		}
		return $aParams;
	}

	/**
	 * Returns a list of routes mapped to the current request method.
	 * @param $iMethod
	 * @return array
	 */

	protected function getRouteArray( $iMethod )
	{
		$aRoutes = [];

		switch( $iMethod )
		{
			case RequestMethod::DELETE:
				$aRoutes = $this->_Delete;
				break;

			case RequestMethod::GET:
				$aRoutes = $this->_Get;
				break;

			case RequestMethod::POST:
				$aRoutes = $this->_Post;
				break;

			case RequestMethod::PUT:
				$aRoutes = $this->_Put;
				break;
		}

		return $aRoutes;
	}

	/**
	 * @param $sUri
	 * @param $iMethod
	 * @return \Notion\Route
	 * @throws \Exception
	 */

	public function getRoute( $iMethod, $sUri )
	{
		$aRoutes = $this->getRouteArray( $iMethod );

		foreach( $aRoutes as $Route )
		{
			if( !$this->isRouteWithParams( $Route ) )
			{
				$aParams = $this->processRoute( $Route, $sUri );

				if( $aParams )
				{
					$Route->Parameters = null;
					return $Route;
				}
			}
		}

		foreach( $aRoutes as $Route )
		{
			$aParams = $this->processRoute( $Route, $sUri );

			if( $this->isRouteWithParams( $Route ) )
			{
				if( $aParams )
				{
					if( is_array( $aParams ) )
					{
						$Route->Parameters = $aParams;
					}
					else
					{
						$Route->Parameters = null;
					}

					return $Route;
				}
			}
		}
		return null;
	}

	protected function executePreFilters( Route $Route )
	{
		foreach( $this->_Filter as $FilterName )
		{
			$Filter = $this->getFilter( $FilterName );
			$Filter->pre( $Route );
		}
	}

	protected function executePostFilters( Route $Route )
	{
		foreach( $this->_Filter as $FilterName )
		{
			$Filter = $this->getFilter( $FilterName );
			$Filter->post( $Route );
		}
	}

	/**
	 * @param \Notion\Route $Route
	 * @return mixed
	 */

	public function dispatch( Route $Route )
	{
		$this->executePreFilters( $Route );

		$Result = $Route->execute( $this );

		$this->executePostFilters( $Route );

		return $Result;
	}

	/**
	 * @param array|null $Argv
	 * @return result of route lambda.
	 * @throws \Exception
	 */

	function run( array $Argv = null )
	{
		if( !$Argv || !array_key_exists( 'route', $Argv ) )
		{
			throw new \Exception( 'Missing route.' );
		}

		if( !$Argv || !array_key_exists( 'type', $Argv ) )
		{
			throw new \Exception( 'Missing method type.' );
		}

		$sType = '';

		if( array_key_exists( 'type', $Argv ) )
		{
			$sType = $Argv[ 'type' ];
		}

		$Route = $this->getRoute( Notion\RequestMethod::getType( $sType ), $Argv[ 'route' ] );

		if( !$Route )
		{
			$Route = $this->getRoute( Notion\RequestMethod::GET, '404' );

			if( $Route )
			{
				$Route->Parameters = $Argv;
			}
			else
			{
				throw new \Exception( "Missing 404 route." );
			}
		}

		if( array_key_exists( 'extra', $Argv ) )
		{
			if( is_array( $Route->Parameters ) )
			{
				$Route->Parameters = array_merge( $Route->Parameters, $Argv[ 'extra' ] );
			}
			else
			{
				$Route->Parameters = $Argv[ 'extra' ];
			}
		}

		return $this->dispatch( $Route );
	}
}
