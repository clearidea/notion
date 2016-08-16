<?php

/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/15/16
 * Time: 5:45 PM
 */
class RequestMethodTest extends PHPUnit_Framework_TestCase
{
	public function testMethod()
	{
		$this->assertEquals(
			\Notion\RequestMethod::getType( 'GET' ),
			\Notion\RequestMethod::GET
		);

		$this->assertEquals(
			\Notion\RequestMethod::getType( 'PUT' ),
			\Notion\RequestMethod::PUT
		);

		$this->assertEquals(
			\Notion\RequestMethod::getType( 'DELETE' ),
			\Notion\RequestMethod::DELETE
		);

		$this->assertEquals(
			\Notion\RequestMethod::getType( 'POST' ),
			\Notion\RequestMethod::POST
		);

		$this->assertEquals(
			\Notion\RequestMethod::getType( 'FOO' ),
			\Notion\RequestMethod::UNKNOWN
		);

	}
}
