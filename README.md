# Notion

Notion is a lightweight router/dispatcher is the vein of Ruby's Sinatra 
or Python's Flask. It allows for a very quick method for creating an app
using restful routes or to add them to an existing application.

* Easily map restful http requests to functions.
* Extract one or many variables from routes using masks.
* Create custom 404 responses.


## Installation

The best way to install Notion is via [Composer](http://getcomposer.org)

Our package is located [here](https://packagist.org/packages/clearidea/notion)

Install Composer

    curl -sS https://getcomposer.org/installer | php

Add the Notion Package

    php composer.phar require clearidea/notion

Install Later Updates

    composer.phar update

## .htaccess
This example .htaccess file shows how to get and pass the route
to the example application.

    RewriteEngine on
    
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    
    RewriteRule ^(.*)$ index.php?route=$1 [L,QSA]

## Example App
Here is an example of a fully functional application that processes
several routes including one with a variable.

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
    
    $Filter = new \Neuron\Data\Filter\Get();
    
    $App->run(
        [
            'route' => $Filter->filterScalar( 'route' )
        ]
    );
