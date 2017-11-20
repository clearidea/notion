<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/15/16
 * Time: 5:45 PM
 */
class RouteTest extends PHPUnit_Framework_TestCase
{
	public function testRouteSuccess()
	{
		try
		{
			$Route = new \Notion\Route( 'method', function() { return 'test';} );

			$this->assertEquals(
				$Route->Path,
				'method'
			);
		}
		catch( Exception $exception )
		{
			$this->fail( $exception->getMessage() );
		}
	}

	public function testRouteFail()
	{
		try
		{
			$Route = new \Notion\Route( 'method', null );

			$this->fail( 'Creation of this route should have failed.' );
		}
		catch( Exception $exception )
		{
		}
	}
}
