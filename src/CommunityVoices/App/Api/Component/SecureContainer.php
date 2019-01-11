<?php

/**
 * @overview Acts as a secure service decorator
 */

namespace CommunityVoices\App\Api\Component;

class SecureContainer
{
    private $identifier;

    private $arbiter;

    public function __construct(Arbiter $arbiter, Contract\CanIdentify $identifier)
    {
        $this->arbiter = $arbiter;
        $this->identifier = $identifier;
    }

    public function contain($decoratedInstance)
    {
        $containedItem = new ContainedItem($decoratedInstance, function ($method, $args, $contained) {
            $user = $this->identifier->identify();

            if (!is_object($contained)) {
                // This should be more verbose, but we cannot print out a class name
                // of a non-object. So, what to do here?
                throw new \Exception('No decorated instance!');
            }

            $signature = get_class($contained) . "::" . $method;

            if (!method_exists($contained, $method)) {
                throw new \Exception('Method not found ' . $signature);
            }

            if (!$this->arbiter->isAllowedForIdentity($signature, $user)) {
                throw new \Exception('Access denied');
            }

            return call_user_func_array([$contained, $method], $args);
        });

        return $containedItem;
    }
}
