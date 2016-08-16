<?php

namespace Notion;

class RequestMethod
{
	const PUT      = 1;
	const POST     = 2;
	const GET      = 3;
	const DELETE   = 5;
	const UNKNOWN  = 256;

	/**
	 * Gets the text string for a type.
	 * @param sMethod type override.
	 * @return int
	 */
	static public function getType( $sMethod = '' )
	{
		/// @todo replace with server->filterscalar()

		if( !$sMethod )
		{
			$method = $_SERVER[ 'REQUEST_METHOD' ];
		}
		else
		{
			$method = $sMethod;
		}

		switch( $method )
		{
			case 'PUT':
				return self::PUT;

			case 'POST':
				return self::POST;

			case 'GET':
				return self::GET;

			case 'DELETE':
				return self::DELETE;

			default:
				return self::UNKNOWN;
		}
	}
}
