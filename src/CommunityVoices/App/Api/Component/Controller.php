<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class Controller
{
    public $secured;

    protected $secureContainer;

    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        $this->secureContainer = $secureContainer;

        $this->secured = false;
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $secured = property_exists($this, "secured") && $this->secured;
            $secureThis = $this->secureContainer->contain($this);

            $methodArray = $secured ? array($this, $method) : array($secureThis, $method);

            return call_user_func_array($methodArray, $arguments);
        }
    }

    protected function send404()
    {
        http_response_code(404);
        echo file_get_contents('https://environmentaldashboard.org/404');
        exit;
    }
}
