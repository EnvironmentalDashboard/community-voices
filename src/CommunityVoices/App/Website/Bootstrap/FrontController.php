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
            return $this->dispatcher->dispatch($request)->send();
        } catch (\Symfony\Component\Routing\Exception\ResourceNotFoundException $e) {
            return $this->notFound();
        } catch (AccessDenied $e) {
            return $this->denied();
        } catch (Exception $e) {
            return $this->fail();
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
     * @todo
     */
    public function notFound()
    {
        // We are going to render our 404 page and put it into
        // this response.
        $response = new Response();
        $response->setStatusCode(404);

        // Render our 404 page.
        $request = Request::create(
            '/community-voices' . '/404',
            'GET'
        );

        $response->setContent($this->doRequest($request)->getContent());

        return $response;
    }

    /**
     * Access denied
     *
     * @todo
     */
    public function denied()
    {
        echo "Access denied";
        exit;
    }
}
