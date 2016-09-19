<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 9/19/16
 * Time: 8:08 AM
 */

namespace Notion;


class RouteParamException extends \Exception
{
	public function __construct( $message )
	{
		parent::__construct( $message );
	}
}
