<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Landing
{
    protected $recognitionAdapter;
    protected $landingAPIController;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\Controller\Landing $landingAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->landingAPIController = $landingAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getLanding($request){
        $apiController = $this->secureContainer->contain($this->landingAPIController);

        $apiController->getLanding($request);
    }

}
