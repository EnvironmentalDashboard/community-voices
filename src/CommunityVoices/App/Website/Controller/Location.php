<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Location
{
    protected $locationAPIController;
    protected $secureContainer;

    public function __construct(
        Api\Controller\Location $locationAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->locationAPIController = $locationAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getAllLocation($request)
    {
        $apiController = $this->secureContainer->contain($this->locationAPIController);

        $apiController->getAllLocation($request);
    }
}
