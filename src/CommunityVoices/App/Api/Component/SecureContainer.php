<?php

/**
 * @overview Acts as a secure service decorator
 */

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\Model\Entity;

class SecureContainer
{
    private $decoratedInstance;

    private $identifier;

    private $arbiter;

    public function __construct(Arbiter $arbiter, Contract\CanIdentify $identifier)
    {
        $this->arbiter = $arbiter;
        $this->identifier = $identifier;
    }

    public function contain($decoratedInstance)
    {
        $this->decoratedInstance = $decoratedInstance;

        return $this;
    }

    public function __call($method, $args)
    {
        $user = $this->identifier->identify();

        if (!is_object($this->decoratedInstance)) {
            throw new \Exception('No decorated instance!');
        }

        if (!method_exists($this->decoratedInstance, $method)) {
            throw new \Exception('Method not found');
        }

        $signature = get_class($this->decoratedInstance) . "::" . $method;

        if (!$this->arbiter->isAllowedForIdentity($signature, $user)) {
            throw new \Exception('Access denied');
        }

        return call_user_func_array([$this->decoratedInstance, $method], $args);
    }
}
