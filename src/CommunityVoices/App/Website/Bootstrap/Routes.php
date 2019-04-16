<?php

namespace CommunityVoices\App\Website\Bootstrap;

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Routes
 */

class Routes
{
    const ROUTES_PATH = __DIR__ . '/../Config/Routes.json';

    public function get()
    {
        /**
         * @config
         */
        $appPrefix = '/community-voices';

        $routes = new RouteCollection();

        $config = json_decode(file_get_contents(self::ROUTES_PATH), true);

        foreach ($config as $name => $options) {
            // Add the website route
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

            // Allow ending a URL in '/'
            $routes->add(
                $name,
                new Route(
                    $appPrefix . $options['notation'] . '/',
                    $options['defaults'],
                    isset($options['requirements']) ? $options['requirements'] : [],
                    [],
                    '',
                    [],
                    isset($options['method']) ? $options['method'] : []
                )
            );

            // Add the API route
            $apiDefaults = [
                'use-api' => true
            ];

            $routes->add(
                "api." . $name,
                new Route(
                    $appPrefix . '/api' . $options['notation'],
                    array_merge($options['defaults'], $apiDefaults),
                    isset($options['requirements']) ? $options['requirements'] : [],
                    [],
                    '',
                    [],
                    isset($options['method']) ? $options['method'] : []
                )
            );

            $routes->add(
                "api." . $name . '/',
                new Route(
                    $appPrefix . '/api' . $options['notation'],
                    array_merge($options['defaults'], $apiDefaults),
                    isset($options['requirements']) ? $options['requirements'] : [],
                    [],
                    '',
                    [],
                    isset($options['method']) ? $options['method'] : []
                )
            );
        }

        return $routes;
    }
}
