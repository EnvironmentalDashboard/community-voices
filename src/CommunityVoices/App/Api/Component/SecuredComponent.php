<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\AccessControl;
use CommunityVoices\App\Api\Component;

class SecuredComponent
{
    private $accessController;

    public function __construct(
        AccessController $accessController
    ) {
        $this->accessController = $accessController;
    }

    // Automatically secures each called function in every API controller / view.
    public function __call($method, $arguments)
    {
        $signature = get_class($this) . "::" . $method;

        if (!method_exists($this, $method)) {
            $this->logger->error('SecuredComponent MethodNotFound Exception', ['message' => 'Method not found ' . $signature]);
            throw new Exception\MethodNotFound('Method not found ' . $signature);
        }

        if (method_exists($this->accessController, $method)) {
            if (!call_user_func_array([$this->accessController, $method], $arguments)) {
                $this->accessDenied($user);
            }
        } else {
            // this should be removed once this feature is shipped, but it is
            // incredibly helpful for debugging
            var_dump('did not find ' . $this->accessController . '::' . $method);
            die();
            $this->accessDenied($user);
        }

        return call_user_func_array([$this, $method], $arguments);
    }
}
