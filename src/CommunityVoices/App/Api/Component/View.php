<?php

namespace CommunityVoices\App\Api\Component;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class View
{
    protected $mapperFactory;
    protected $secureContainer;

    public function __construct(
        MapperFactory $mapperFactory,
        Component\SecureContainer $secureContainer
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->secureContainer = $secureContainer;
    }

    /*
     * Automatically secures each called function in every API view.
     * Note that this is somewhat hacked into making it work with the
     * SecureContainer.
     * A future implementation could easily make SecureContainer obsolete
     * by simply providing its functionality in this function.
     *
     * Also note that this is copied code from the Controller object.
     * It would be better to move this such that duplicate code is not repeated.
     */
    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $secured = property_exists($this, "secured") && $this->secured;
            $secureThis = $this->secureContainer->contain($this);

            $methodArray = $secured ? array($this, $method) : array($secureThis, $method);

            // We want `secured` to be a property specific to each method
            // call, so we will remove it when it is done.
            if ($secured) {
                unset($this->secured);
            }

            return call_user_func_array($methodArray, $arguments);
        } else {
            throw new Exception\MethodNotFound("Method not found " . get_class($this) . "::" . $method);
        }
    }

    protected function errorsResponse($key)
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        // In the case that we have retrieved errors, we will send them along.
        // Otherwise, our errors array will be an empty array.
        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries($key))
            ? $clientStateObserver->getEntriesBySubject($key) : [];

        $response = new HttpFoundation\JsonResponse(['errors' => $errors]);

        return $response;
    }
}
