<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Location extends Component\Controller
{
    protected $secureContainer;
    protected $locationLookup;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Service\LocationLookup $locationLookup
    ) {
        parent::__construct($secureContainer);

        $this->secureContainer = $secureContainer;
        $this->locationLookup = $locationLookup;
    }

    protected function getAllLocation($request)
    {
        $this->locationLookup->findAll2();
    }
}
