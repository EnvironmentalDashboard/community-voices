<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;
use CommunityVoices\App\Api\AccessControl;

class Location extends Component\Controller
{
    protected $locationLookup;

    public function __construct(
        AccessControl\Location $locationAccessControl,

        Service\LocationLookup $locationLookup
    ) {
        parent::__construct($locationAccessControl);

        $this->locationLookup = $locationLookup;
    }

    protected function getAllLocation($request)
    {
        $this->locationLookup->findAll();
    }
}
