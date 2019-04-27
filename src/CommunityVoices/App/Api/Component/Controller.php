<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class Controller
{
    protected $secureContainer;

    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        $this->secureContainer = $secureContainer;
    }

    public function __call($method, $arguments)
    {
        if (method_exists($this, $method)) {
            $secureThis = $this->secureContainer->contain($this);
            return call_user_func_array(array($secureThis, $method), $arguments);
        } else {
            // Check if our method ends with our secured string,
            // and if so, remove it and try again.
            if (substr_compare($method, $this->secureContainer::SECURED,
                strlen($method) - strlen($this->secureContainer::SECURED), strlen($this->secureContainer::SECURED))) {
                if (method_exists($this, substr($method, 0, -strlen($this->secureContainer::SECURED)))) {
                    return call_user_func_array(array($this, substr($method, 0, -strlen($this->secureContainer::SECURED))), $arguments);
                }
            }
        }
    }

    protected function send404()
    {
        http_response_code(404);
        echo file_get_contents('https://environmentaldashboard.org/404');
        exit;
    }
}
