<?php

namespace Notion;

class Route
{
	public $Path;
	public $Function;
	public $Parameters;
	public $Filter;

	/**
	 * Route constructor.
	 * @param $path string route path i.e. /part/new or /part/:id
	 * @param $function the function to call on a matching route.
	 * @param $Filter string the name of the filter to match with this route.
	 * @throws \Exception
	 */

	public function __construct( $path, $function, $Filter = null )
	{
		if( !is_callable( $function ) )
		{
			throw new \Exception( 'Route: function not callable.' );
		}

		$this->Path       = $path;
		$this->Function   = $function;
		$this->Parameters = null;
		$this->Filter     = $Filter;
	}

	/**
	 * Extracts the template array from the route definition.
	 * @return array
	 * @throws \Exception
	 */

	public function parseParams()
	{
		$aDetails = [];

		$aParts = explode( '/', $this->Path );
		array_shift( $aParts );

		foreach( $aParts as $sPart )
		{
			if( substr( $sPart, 0, 1 ) == ':' )
			{
				$Param = substr( $sPart, 1 );

				$this->checkForDuplicateParams( $Param, $aDetails );

				$aDetails[] = [
					'param'  => $Param,
					'action' => false
				];
			}
			else
			{
				$aDetails[] = [
					'param'  => false,
					'action' => $sPart
				];
			}
		}
		return $aDetails;
	}

	/**
	 * @param $Param
	 * @param $Params
	 * @throws RouteParamException
	 */

	protected function checkForDuplicateParams( $Param, $Params )
	{
		foreach( $Params as $Current )
		{
			if( $Param == $Current[ 'param' ] )
			{
				throw new RouteParamException( "Duplicate parameter '$Param' found for route {$this->Path}'." );
			}
		}
	}

	/**
	 * @param Router $Router
	 * @return mixed
	 */
	public function execute( Router $Router )
	{
		$Filter = null;

		if( $this->Filter )
		{
			$Filter = $Router->getFilter( $this->Filter );
		}

		if( $Filter )
		{
			$Filter->pre();
		}

		$Function = $this->Function;

		$Result = $Function( $this->Parameters );

		if( $Filter )
		{
			$Filter->post();
		}

		return $Result;
	}
}
