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
        Api\Controller\Image $imageAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->imageAPIController = $imageAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getLanding(){
        // Intentionally blank
    }

}
