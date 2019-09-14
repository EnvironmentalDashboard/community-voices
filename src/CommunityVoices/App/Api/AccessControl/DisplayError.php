<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class DisplayError extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function getError()
    {
        return true;
    }
}
