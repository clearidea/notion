<?php

class FilterTest extends PHPUnit_Framework_TestCase
{
	public $Router;

	public function setup()
	{
		$this->Router = new \Notion\Router();
	}

	public function testRoutePreFilter()
	{
		$Filter = false;

		$this->Router->registerFilter(
			'PreFilter',
			new \Notion\Filter(
				function() use ( &$Filter ) { $Filter = true; }
			)
		);

		$this->Router->get(
			'/test',
			function(){},
			'PreFilter'
			);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::GET,
			'/test'
		);

		$this->assertNotEmpty( $Route );

		$this->Router->dispatch( $Route );

		$this->assertTrue( $Filter );
	}

	public function testRoutePostFilter()
	{
		$Filter = false;

		$this->Router->registerFilter(
			'PostFilter',
			new \Notion\Filter(
				null,
				function() use ( &$Filter ) { $Filter = true; }
			)
		);

		$this->Router->get(
			'/test',
			function(){},
			'PostFilter'
		);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::GET,
			'test'
		);

		$this->Router->dispatch( $Route );

		$this->assertTrue( $Filter );
	}

	public function testGlobalPreFilter()
	{
		$Filter = false;

		$this->Router->registerFilter(
			'PreFilter',
			new \Notion\Filter(
				function() use ( &$Filter ) { $Filter = true; }
			)
		);

		$this->Router->addFilter( 'PreFilter' );
		$this->Router->get(
			'/test',
			function(){}
		);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::GET,
			'/test'
		);

		$this->assertNotEmpty( $Route );

		$this->Router->dispatch( $Route );

		$this->assertTrue( $Filter );
	}

	public function testGlobalPostFilter()
	{
		$Filter = false;

		$this->Router->registerFilter(
			'PostFilter',
			new \Notion\Filter(
				null,
				function() use ( &$Filter ) { $Filter = true; }
			)
		);

		$this->Router->addFilter( 'PostFilter' );

		$this->Router->get(
			'/test',
			function(){}
		);

		$Route = $this->Router->getRoute(
			Notion\RequestMethod::GET,
			'test'
		);

		$this->Router->dispatch( $Route );

		$this->assertTrue( $Filter );
	}

}