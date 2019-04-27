<?php

/**
 * @overview Acts as a secure service decorator
 */

namespace CommunityVoices\App\Api\Component;

class SecureContainer
{
    // What we add to methods to indicate them as secure.
    public const SECURED = "__SECURE";

    private $identifier;
    private $arbiter;
    private $logger;

    public function __construct(
        Arbiter $arbiter,
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->arbiter = $arbiter;
        $this->identifier = $identifier;
        $this->logger = $logger;
    }

    public function contain($decoratedInstance)
    {
        $containedItem = new ContainedItem($decoratedInstance, function ($method, $args, $contained) {
            $user = $this->identifier->identify();

            if (!is_object($contained)) {
                $this->logger->error('SecureContainer InvalidArg Exception', ['message' => 'Expected to contain an object, but received type ' . gettype($contained)]);
                throw new \InvalidArgumentException('Expected to contain an object, but received type ' . gettype($contained));
            }

            $signature = get_class($contained) . "::" . $method;

            if (!method_exists($contained, $method)) {
                $this->logger->error('SecureContainer MethodNotFound Exception', ['message' => 'Method not found ' . $signature]);
                throw new Exception\MethodNotFound('Method not found ' . $signature);
            }

            if (!$this->arbiter->isAllowedForIdentity($signature, $user)) {
                $this->logger->error('SecureContainer AccessDenied Exception', ['message' => 'Access denied']);
                throw new Exception\AccessDenied('Access denied');
            }

            // In order for us not to get into a SecureContainer loop,
            // we need to have an indication that we have secured this
            // method.
            return call_user_func_array([$contained, $method . self::SECURED], $args);
        });

        return $containedItem;
    }
}
