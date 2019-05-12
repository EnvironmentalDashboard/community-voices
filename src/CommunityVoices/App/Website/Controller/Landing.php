<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Landing
{
    protected $landingAPIController;

    public function __construct(
        Api\Controller\Landing $landingAPIController
    ) {
        $this->landingAPIController = $landingAPIController;
    }

    public function getLanding($request)
    {
        $this->landingAPIController->getLanding($request);
    }
}
