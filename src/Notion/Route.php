<?php

namespace Notion;

class Route
{
	public static function delete( string $Route, $Function ) : Router
	{
		$Router = Router::getInstance();

		return $Router->delete( $Route, $Function );
	}

	public static function get( string $Route, $Function ) : Router
	{
		$Router = Router::getInstance();

		return $Router->get( $Route, $Function );
	}

	public static function post( string $Route, $Function ) : Router
	{
		$Router = Router::getInstance();

		return $Router->post( $Route, $Function );
	}

	public static function put( string $Route, $Function ) : Router
	{
		$Router = Router::getInstance();

		return $Router->put( $Route, $Function );
	}

	public static function dispatch( array $Params )
	{
		return Router::getInstance()->run( $Params );
	}
}
