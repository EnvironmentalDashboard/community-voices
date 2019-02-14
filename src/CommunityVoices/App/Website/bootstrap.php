<?php

/**
 * @codeCoverageIgnore
 * @overview Bootstraps the application together
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use CommunityVoices\App\Website;
use CommunityVoices\Model;

/**
 * Set time zone
 */
date_default_timezone_set('America/New_York');

require __DIR__ . '/../../../../vendor/autoload.php';

$production_server = (getenv('SERVER') === 'environmentaldashboard.org');

/**
 * Injector
 */
$injector = new Auryn\Injector;

/**
 * Db handler configuration
 */
require 'db.php';

$injector->share($dbHandler);

/**
 * Create and share log (required by Palladium)
 */

$logger = new Monolog\Logger('name');
$logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../../../../log/access.log'));

$injector->share($logger);

$injector->alias('Psr\Log\LoggerInterface', 'Monolog\Logger');

/**
 * Import routes
 */

$locator = new Symfony\Component\Config\FileLocator(array(__DIR__ . '/Config'));
$loader = new Symfony\Component\Routing\Loader\PhpFileLoader($locator);

$routes = $loader->load('Routes.php');

/**
 * Routing the request
 */

$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();

$context = new Symfony\Component\Routing\RequestContext();
$context->fromRequest($request);

$matcher = new Symfony\Component\Routing\Matcher\UrlMatcher($routes, $context);

$uri = isset($_SERVER['REQUEST_URI'])
            ? $_SERVER['REQUEST_URI']
            : '/';

$uri = ($production_server) ? '/community-voices' . substr(explode('?', $uri)[0], 1) : explode('?', $uri)[0]; // TODO: fix!

try {
    $parameters = new Symfony\Component\HttpFoundation\ParameterBag($matcher->match($uri));
} catch (Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
    http_response_code(404);
    echo file_get_contents('https://environmentaldashboard.org/404'); // should prob include 404 page in this app
    exit;
}

$request->attributes = $parameters;

/**
 * Create and share URL generator
 */

$urlGenerator = new  Symfony\Component\Routing\Generator\UrlGenerator($routes, $context);
$injector->share($urlGenerator);

/**
 * Create and share mapper factories
 */

$websiteMapperFactory = new Website\Component\MapperFactory($request);
$modelMapperFactory = new Model\Component\MapperFactory($dbHandler);
$pdMapperFactory = new Palladium\Component\MapperFactory($dbHandler, '`community-voices_identities`');

$injector->share($websiteMapperFactory);
$injector->share($modelMapperFactory);
$injector->share($pdMapperFactory);

$injector->alias('Palladium\Contract\CanCreateMapper', 'Palladium\Component\MapperFactory');

/**
 * Create and share access arbiter
 */

$aclRaw = json_decode(file_get_contents(__DIR__  . '/../Api/Config/AccessControlList.json'), true);

$arbiter = new CommunityVoices\App\Api\Component\Arbiter($aclRaw['roles'], $aclRaw['rules']);

$injector->share($arbiter);

/**
 * Alias CanIdentify depdencies with the local recognition service
 */

$injector->alias('CommunityVoices\App\Api\Component\Contract\CanIdentify', 'CommunityVoices\App\Website\Component\RecognitionAdapter');


/**
 * Configure mail server
 * If no file /opendkim/mail.private exists, we will not use the mailer.
 */
define("USE_MAILER", file_exists("/opendkim/mail.private"));

$mailerFactory = function () {
    $transport = new Swift_SendmailTransport('/usr/sbin/sendmail -bs');

    $defaultsPlugin = new Finesse\SwiftMailerDefaultsPlugin\SwiftMailerDefaultsPlugin([
        'from' => ['no-reply@environmentaldashboard.org' => 'Environmental Dashboard'],
    ]);

    $mailer = new Swift_Mailer($transport);
    $mailer->registerPlugin($defaultsPlugin);

    return $mailer;
};

$injector->delegate('Swift_Mailer', $mailerFactory);

$injector->define('Swift_Signers_DKIMSigner', [
    ':privateKey' => USE_MAILER ? file_get_contents('/opendkim/mail.private') : NULL,
    ':domainName' => 'environmentaldashboard.org',
    ':selector' => 'mail',
    ':passphrase' => ''
]);

/**
 * Processing request
 */

$resource = $request->attributes->get('resource');
$action = $request->attributes->get('action');
$method = $request->attributes->get('method');

$controller = $injector->make("CommunityVoices\\App\\Website\\Controller\\" . $resource); // will call the website controller which will call the api controller to gather requested data using services (which use mappers to make queries to database) and store in an object referenced in the api view as $stateObserver
$controller->{$action}($request);

$view = $injector->make("CommunityVoices\\App\\Website\\View\\" . $resource); // will call the website view which will set xslt template, variables, etc. and format a response, getting data from the api view which will fetch data from the $stateObserver array (api view usually called very early in website view)
$response = $view->{$action}($request);

$response->send();
