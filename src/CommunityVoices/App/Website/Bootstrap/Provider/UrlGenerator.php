<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;
use Symfony\Component\Routing\RouteCollection;

/**
 * @overview Url generator providerc
 */

class UrlGenerator extends Provider
{
    protected $routes;
    protected $request;

    public function __construct($injector, RouteCollection $routes, $request)
    {
        parent::__construct($injector);

        $this->routes = $routes;
        $this->request = $request;
    }

    public function init()
    {
        $context = new \Symfony\Component\Routing\RequestContext();
        $context->fromRequest($this->request);

        $urlGenerator = new \Symfony\Component\Routing\Generator\UrlGenerator($this->routes, $context);
        $this->injector->share($urlGenerator);
    }
}
