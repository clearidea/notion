<?php

namespace Notion;

class Filter
{
	private $_PreFn;
	private $_PostFn;

	public function __construct( $PreFn, $PostFn = null )
	{
		$this->_PreFn  = $PreFn;
		$this->_PostFn = $PostFn;
	}

	public function pre()
	{
		if( !$this->_PreFn )
		{
			return null;
		}

		$Function = $this->_PreFn;

		return $Function();
	}

	public function post()
	{
		if( !$this->_PostFn )
		{
			return null;
		}

		$Function = $this->_PostFn;

		return $Function();
	}
}
