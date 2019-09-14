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

    public function accessDenied()
    {
        $this->logger->error('AccessController AccessDenied Exception', ['message' => 'Access denied']);
        throw new Exception\AccessDenied($this->getUser());
    }
}
