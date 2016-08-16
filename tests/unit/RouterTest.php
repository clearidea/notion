<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/15/16
 * Time: 5:45 PM
 */
class RouterTest extends PHPUnit_Framework_TestCase
{
	public $Router;

	public function setup()
	{
		$this->Router = new \Notion\Router();
	}

	public function testDelete()
	{
		$this->Router->delete(
			'/delete/:id',
			function()
			{
				return 'delete';
			}
		);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::DELETE,
			'delete/1'
		);

		$this->assertNotNull(
			$Route
		);

		$this->assertEquals(
			$Route->path,
			'/delete/:id'
		);
	}

	public function testGet()
	{
		$this->Router->get( '/get/:id', function(){ return 'get'; } );

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::GET,
			'get/1'
		);

		$this->assertNotNull(
			$Route
		);

		$this->assertEquals(
			$Route->path,
			'/get/:id'
		);
	}

	public function testPost()
	{
		$this->Router->post( '/post', function(){ return 'post'; } );

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::POST,
			'post'
		);

		$this->assertNotNull(
			$Route
		);

		$this->assertEquals(
			$Route->path,
			'/post'
		);
	}

	public function testPut()
	{
		$this->Router->put( '/put', function(){ return 'put'; } );

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::PUT,
			'put'
		);

		$this->assertNotNull(
			$Route
		);

		$this->assertEquals(
			$Route->path,
			'/put'
		);
	}

	public function testDispatch()
	{
		$this->Router->delete(
			'/delete/:id',
			function( $parameters )
			{
				echo "id=$parameters[id]";
			}
		);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::DELETE,
			'delete/1'
		);

		$this->Router->dispatch( $Route );
	}

	public function testRunSuccess()
	{
		$this->Router->get( '/', function(){} );

		try
		{
			$this->Router->run( [ 'route' => '/', 'type' => 'GET' ] );
		}
		catch( Exception $exception )
		{
			$this->fail( $exception->getMessage() );
		}
	}

	public function testRunMissingRoute()
	{
		$this->Router->get( '/', function(){} );

		try
		{
			$this->Router->run();
			$this->fail( "Should have failed due to missing route." );
		}
		catch( Exception $exception )
		{
		}
	}

	public function testRun404Fail()
	{
		$this->Router->get( '/', function(){} );

		try
		{
			$this->Router->run( [ 'route' => 'foo', 'type' => 'GET' ] );
			$this->fail( 'Should fail processing route.' );
		}
		catch( Exception $exception )
		{
		}
	}

	public function testRun404Success()
	{
		$this->Router->get( '/', function(){} );

		$this->Router->get( '/404', function(){} );

		try
		{
			$this->Router->run( [ 'route' => 'foo', 'type' => 'GET' ] );
			$this->fail( 'Should fail processing route.' );
		}
		catch( Exception $exception )
		{
		}
	}

}
