<?php

namespace CommunityVoices\App\Website\Bootstrap;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use CommunityVoices\Api\Component\Exception\AccessDenied;
use CommunityVoices\Api\Component\Exception\MethodNotFound;

/**
 * @overview Front controller to bootstrap the CommunityVoices application together
 *
 * Adapted from Fowler's POEAA: Front Controller
 */

class FrontController
{
    protected $router;
    protected $dispatcher;
    protected $injector;

    /**
     * Providers to initialize prior to request route & dispatching
     *
     * Note that the routes provider is excluded because it sould be initialized
     * already.
     *
     * @var array
     */
    protected $providers = [
        'CommunityVoices\App\Website\Bootstrap\Provider\Database',
        'CommunityVoices\App\Website\Bootstrap\Provider\Logger',
        'CommunityVoices\App\Website\Bootstrap\Provider\Mappers',
        'CommunityVoices\App\Website\Bootstrap\Provider\Recognition',
        'CommunityVoices\App\Website\Bootstrap\Provider\Swift',
        'CommunityVoices\App\Website\Bootstrap\Provider\AccessControl',
        'CommunityVoices\App\Website\Bootstrap\Provider\UrlGenerator'
    ];

    public function __construct($router, $dispatcher, $injector)
    {
        $this->router = $router;
        $this->dispatcher = $dispatcher;
        $this->injector = $injector;
    }

    public function doRequest($request)
    {
        $this->loadProviders($request);

        try {
            $this->router->route($request);
            $this->dispatcher->dispatch($request)->send();
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
            $this->notFound($request)->send();
        } catch (AccessDenied $e) {
            $this->denied($request)->send();
        } catch (Exception $e) {
            $this->fail();
        }
    }

    protected function loadProviders($request)
    {
        foreach ($this->providers as $providerClass) {
            try {
                $provider = $this->injector->make($providerClass, [
                    ':injector' => $this->injector,
                    ':request' => $request,
                    ':routes' => $this->router->getRoutes()
                ]);

                $provider->init();
            } catch (Exception $e) {
                $this->logger->alert('Failure loading application provider', [
                    'provider' => $providerClass,
                    'exception' => [
                        'type' => get_class($e),
                        'message' => $e->getMessage()
                    ]
                ]);

                $this->fail();
            }
        }
    }

    /**
     * A crucial application component failed to load
     *
     * Creates a failure response
     * @todo
     */
    public function fail()
    {
        echo "Failure";
        exit;
    }

    /**
     * A route was not found
     *
     * Creates a 404 response
     */
    public function notFound($request)
    {
        // Switch our resource and action to what we would rather have.
        $request->attributes->set('resource', 'Display404');
        $request->attributes->set('action', 'get404');

        // Then, have the dispatcher dispatch this alternate request.
        return $this->dispatcher->dispatch($request);
    }

    /**
     * Access denied
     *
     * @todo
     */
    public function denied()
    {
        // Switch our resource and action to what we would rather have.
        $request->attributes->set('resource', 'AccessDenied');
        $request->attributes->set('action', 'getAccessDenied');

        // Then, have the dispatcher dispatch this alternate request.
        return $this->dispatcher->dispatch($request);
    }
}
