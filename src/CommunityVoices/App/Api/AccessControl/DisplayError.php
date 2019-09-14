<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class DisplayError extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public function getError()
    {
        return true;
    }
}
