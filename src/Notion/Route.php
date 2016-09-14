<?php

namespace Notion;

class Route
{
	public $path;
	public $function;
	public $parameters;

	public function __construct( $path, $function )
	{
		if( !is_callable( $function ) )
		{
			throw new \Exception( 'Route: function not callable.' );
		}

		$this->path       = $path;
		$this->function   = $function;
		$this->parameters = null;
	}

	/**
	 * Extracts the template array from the route definition.
	 * @param $Route
	 * @return array
	 */

	public function parseParams()
	{
		$aDetails = [];

		$aParts = explode( '/', $this->path );
		array_shift( $aParts );

		foreach( $aParts as $sPart )
		{
			if( substr( $sPart, 0, 1 ) == ':' )
			{
				$aDetails[] = array(
					'param'  => substr( $sPart, 1 ),
					'action' => false
				);
			}
			else
			{
				$aDetails[] = array(
					'param'  => false,
					'action' => $sPart
				);
			}
		}
		return $aDetails;
	}
}
