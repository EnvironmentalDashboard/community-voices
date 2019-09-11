<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Location extends Component\Controller
{
    protected $locationLookup;

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver,

        Service\LocationLookup $locationLookup
    ) {
        parent::__construct($identifier, $logger, $stateObserver);

        $this->locationLookup = $locationLookup;
    }

    protected function getAllLocation($request)
    {
        $this->locationLookup->findAll();
    }
}
