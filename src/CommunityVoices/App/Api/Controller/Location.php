<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Location extends Component\Controller
{
    protected $locationLookup;

    public function __construct(
      Service\LocationLookup $locationLookup
    ) {
        $this->locationLookup = $locationLookup;
    }

    public function getAllImage($request)
    {
        $this->locationLookup->findAll2();
    }
}
