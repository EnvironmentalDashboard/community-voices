<?php

namespace CommunityVoices\App\Website\Bootstrap;

/**
 * @overview esponsible for taking a request and dispatching it to a controller
 * & view.
 */

class Dispatcher
{
    const API_CONTROLLER_SIGNATURE = "CommunityVoices\\App\\Api\\Controller\\";
    const CONTROLLER_SIGNATURE = "CommunityVoices\\App\\Website\\Controller\\";
    const API_VIEW_SIGNATURE = "CommunityVoices\\App\\Api\\View\\";
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

        /**
         * Instantiate controller & call requested action
         */
        $controllerSignature = self::CONTROLLER_SIGNATURE;

        if ($request->attributes->has('use-api')) {
            $controllerSignature = self::API_CONTROLLER_SIGNATURE;
        }

        $controller = $this->injector->make($controllerSignature . $resource);
        $controller->{$action}($request);

        /**
         * Instantiate view & return the response
         */
        $viewSignature = self::VIEW_SIGNATURE;

        // Check if API view was requested
        if ($request->attributes->has('use-api')) {
            $viewSignature = self::API_VIEW_SIGNATURE;
        }

        $view = $this->injector->make($viewSignature . $resource);
        $response = $view->{$action}($request);

        return $response;
    }
}
