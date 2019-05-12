<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Location
{
    protected $locationAPIController;

    public function __construct(
        Api\Controller\Location $locationAPIController
    ) {
        $this->locationAPIController = $locationAPIController;
    }

    public function getAllLocation($request)
    {
        $this->locationAPIController->getAllLocation($request);
    }
}
