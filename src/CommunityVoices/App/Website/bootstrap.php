<?php

/**
 * @codeCoverageIgnore
 * @overview Bootstraps the application together
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use CommunityVoices\App\Website;

require __DIR__ . '/../../../../vendor/autoload.php';

/*
 * Setting up request & response abstraction
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

$builder = new Fracture\Http\ResponseBuilder($request);
$response = $builder->create();

/**
 * Import routes and prepare by prepending application prefix
 */

$config = json_decode(file_get_contents(__DIR__ . '/Config/Routes.json'), true);

$routes = array_map(function ($entry) {
    $appPrefix = '/oberlin/community-voices';

    $entry['notation'] = $appPrefix . $entry['notation'];
    return $entry;
}, $config);

/**
 * Injector
 */
$injector = new Auryn\Injector;

/**
 * Db handler configuration
 */

$dbHandler = new PDO('mysql:host=localhost;dbname=community_voices;charset=utf8', 'jeremy', '/Bms^zCN%');

$injector->share($dbHandler);

/**
 * Create and share log
 */

$logger = new Monolog\Logger('name');
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../../../../log/access.log'));

$injector->share($logger);

$injector->alias('Psr\Log\LoggerInterface', 'Monolog\Logger');

/**
 * Create and share mapper factories
 */

$websiteMapperFactory = new Website\Component\MapperFactory($request, $response);
$pdMapperFactory = new Palladium\Component\MapperFactory($dbHandler, '`community-voices_identities`');

$injector->share($websiteMapperFactory);
$injector->share($pdMapperFactory);

$injector->alias('Palladium\Contract\CanCreateMapper', 'Palladium\Component\MapperFactory');

/**
 * Routing the request
 */

$router = new Fracture\Routing\Router(new Fracture\Routing\RouteBuilder);
$router->import($routes);

$router->route($request);

/**
 * Processing request
 */

$resource = $request->getParameter('resource');
$action = $request->getParameter('action');
$method = $request->getParameter('method');

if (strtoupper($method) !== strtoupper($request->getMethod())) {
    /**
     * Invalid request - wrong method type
     */

    die('Invalid request');
}

$controller = $injector->make("CommunityVoices\\App\\Website\\Controller\\" . $resource);
$controller->{$action}($request);

$view = $injector->make("CommunityVoices\\App\\Website\\View\\" . $resource);
$view->{$action}($response);
