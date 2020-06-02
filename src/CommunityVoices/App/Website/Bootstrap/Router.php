<?php

namespace CommunityVoices\App\Website\Bootstrap;

use Symfony\Component\Routing\RouteCollection;

/**
 * @overview Bridge for Symfony's router
 */

class Router
{
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    public function route($request)
    {
        /**
         * Routing the request
         */
        $context = new \Symfony\Component\Routing\RequestContext();
        $context->fromRequest($request);

        $matcher = new \Symfony\Component\Routing\Matcher\UrlMatcher($this->routes, $context);

        /**
         * @config
         * @todo
         */
        $production_server = (getenv('SERVER') === 'environmentaldashboard.org');

        $uri = isset($_SERVER['REQUEST_URI'])
                    ? $_SERVER['REQUEST_URI']
                    : '/';

        $uri = $request->getPathInfo();

        $parameters = new \Symfony\Component\HttpFoundation\ParameterBag($matcher->match($uri));
        $request->attributes = $parameters;
    }

    public function getRoutes()
    {
        return $this->routes;
    }

    public function isLocal()
    {
      if((getenv('SERVER') === 'environmentaldashboard.org')) {
        return false;
      }
      return true;
    }
}
