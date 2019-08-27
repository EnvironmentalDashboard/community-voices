<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Location extends Component\Controller
{
    protected $locationLookup;

    public function __construct(
        Component\Arbiter $arbiter,
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,

        Service\LocationLookup $locationLookup
    ) {
        parent::__construct($arbiter, $identifier, $logger);

        $this->locationLookup = $locationLookup;
    }

    protected function getAllLocation($request)
    {
        $this->locationLookup->findAll2();
    }
}
