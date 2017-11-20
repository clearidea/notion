<?php
require_once '../vendor/autoload.php';

$App = new Notion\Router();

$App->get( '/',
		function()
		{
			echo 'Home Page';
		}
	)
	->get( '/about',
		function()
		{
			echo 'About Page';
		}
	)
	->get( '/test/:name',
		function( $parameters )
		{
			echo "Name = $parameters[name]";
		}
	)
	->get( '/404',
		function( $parameters )
		{
			echo "No route found for $parameters[route]";
		}
	);

$Get    = new \Neuron\Data\Filter\Get();
$Server = new \Neuron\Data\Filter\Server();

$App->run(
	[
		'route' => $Get->filterScalar( 'route' ),
		'type'  => $Server->filterScalar( 'METHOD' )
	]
);
