<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\App\Api\Component;

class DisplayError extends Component\Controller
{
    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    protected function getError()
    {

    }
}
