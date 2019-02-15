<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Routes provider
 */

class Routes extends Provider {
    const ROUTES_PATH = __DIR__ . '/../../Config/Routes.json';

    public function init()
    {
        /**
         * @config
         */
        $appPrefix = '/community-voices';

        $routes = new RouteCollection();
        
        $config = json_decode(file_get_contents(self::ROUTES_PATH), true);
    
        foreach ($config as $name => $options) {
            $routes->add(
                $name,
                new Route(
                    $appPrefix . $options['notation'],
                    $options['defaults'],
                    isset($options['requirements']) ? $options['requirements'] : [],
                    [],
                    '',
                    [],
                    isset($options['method']) ? $options['method'] : []
                )
            );
        }

        $this->injector->define('CommunityVoices\App\Website\Bootstrap\Router', [':routes' => $routes]);
    }
}