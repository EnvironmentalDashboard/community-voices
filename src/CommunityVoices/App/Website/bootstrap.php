<?php

// Set-up
use CommunityVoices\App\Website;
use CommunityVoices\App\Website\Bootstrap\Provider;
use CommunityVoices\App\Website\Bootstrap;
use CommunityVoices\Model;

// Timezone
date_default_timezone_set('America/New_York');

// Error settings
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Composer autoloading
require __DIR__ . '/../../../../vendor/autoload.php';

// Injector
$injector = new Auryn\Injector;

// Initialize the request
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

// Initialize the routes provider
$routes = new Bootstrap\Routes;

// Instantiate the front controller
$controller = new Bootstrap\FrontController(
    new Bootstrap\Router($routes->init()),
    new Bootstrap\Dispatcher($injector),
    $injector
);

// Start the request
$controller->doRequest($request);