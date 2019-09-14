<?php

namespace CommunityVoices\App\Api\Component;

class AccessController
{
    private $identifier;
    private $logger;

    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        $this->identifier = $identifier;
        $this->logger = $logger;
    }

    // Maybe worth just storing in the class itself.
    protected function getUser()
    {
        return $this->identifier->identify();
    }

    // Reruns the access control function and throws an exception appropriately.
    public function redoAccessControl()
    {
        // Gets the name of the calling function, as that will determine which
        // function inside of this class is run.
        $function = debug_backtrace()[1]['function'];

        if (!method_exists($this, $function) || !call_user_func_array([$this, $function], [[]])) {
            $this->accessDenied();
        }
    }

    public function accessDenied()
    {
        $this->logger->error('AccessController AccessDenied Exception', ['message' => 'Access denied']);
        throw new Exception\AccessDenied($this->getUser());
    }
}
