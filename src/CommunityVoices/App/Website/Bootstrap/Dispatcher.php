<?php

namespace CommunityVoices\App\Website\Bootstrap;

/**
 * @overview esponsible for taking a request and dispatching it to a controller
 * & view.
 */

class Dispatcher {

    const CONTROLLER_SIGNATURE = "CommunityVoices\\App\\Website\\Controller\\";
    const VIEW_SIGNATURE = "CommunityVoices\\App\\Website\\View\\";

    protected $injector;

    public function __construct($injector)
    {
        $this->injector = $injector;
    }

    public function dispatch($request)
    {
        $resource = $request->attributes->get('resource');
        $action = $request->attributes->get('action');

        $controller = $this->injector->make(self::CONTROLLER_SIGNATURE . $resource);
        $controller->{$action}($request);

        $view = $this->injector->make(self::VIEW_SIGNATURE . $resource);
        $response = $view->{$action}($request);

        return $response;
    }
}