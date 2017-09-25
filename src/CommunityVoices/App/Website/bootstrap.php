<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/**
* @overview Bootstraps the application together
*
*/

require __DIR__ . '/../../../vendor/autoload.php';

/*
 * Setting up request abstraction
 */

$builder = new Fracture\Http\RequestBuilder;
$request = $builder->create([
    'get'    => $_GET,
    'files'  => $_FILES,
    'server' => $_SERVER,
    'post'   => $_POST,
    'cookies'=> $_COOKIE,
]);

$uri = isset($_SERVER['REQUEST_URI'])
           ? $_SERVER['REQUEST_URI']
           : '/';

$request->setUri($uri);

/**
 * Import routes and prepare by prepending application prefix
 */

$config = json_decode(file_get_contents(__DIR__ . '/Config/Routes.json'), true);

$routes = array_map(function ($entry) {
    $appPrefix = '/oberlin/community-voices';

    $entry['notation'] = $appPrefix . $entry['notation'];
    return $entry;
}, $config);

/*
 * Routing the request
 */

$router = new Fracture\Routing\Router(new Fracture\Routing\RouteBuilder);
$router->import($routes);

$router->route($request);