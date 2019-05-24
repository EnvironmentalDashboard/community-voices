<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class SecuredComponent
{
    protected $secureContainer;

    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        $this->secureContainer = $secureContainer;
    }

    /*
     * Automatically secures each called function in every API controller / view.
     * Note that this is somewhat hacked into making it work with the
     * SecureContainer.
     * A future implementation could easily make SecureContainer obsolete
     * by simply providing its functionality in this function.
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
}
