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
}
