<?php

namespace Notion;

class Route
{
	public $Path;
	public $Function;
	public $Parameters;

	/**
	 * Route constructor.
	 * @param $path string route path i.e. /part/new or /part/:id
	 * @param $function function the function to call on a matching route.
	 * @throws \Exception
	 */

	public function __construct( $path, $function )
	{
		if( !is_callable( $function ) )
		{
			throw new \Exception( 'Route: function not callable.' );
		}

		$this->Path       = $path;
		$this->Function   = $function;
		$this->Parameters = null;
	}

	/**
	 * Extracts the template array from the route definition.
	 * @return array
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
}
