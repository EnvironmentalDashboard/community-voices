<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Image
{
    protected $recognitionAdapter;
    protected $imageAPIController;
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

    public function sendImage($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);

        $apiController->sendImage($request);
    }

    public function getImage($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);

        $apiController->getImage($request);
    }

    public function getAllImage($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);

        $apiController->getAllImage($request);
    }

    public function getImageUpload($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);
        $apiController->getImageUpload($request);
    }

    public function postImageUpload($request)
    {
      $apiController = $this->secureContainer->contain($this->imageAPIController);
      $identity = $this->recognitionAdapter->identify();

      $apiController->postImageUpload($request, $identity);
    }

    public function getImageUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);

        $apiController->getImage($request);
    }

    public function postImageUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->imageAPIController);

        $apiController->postImageUpdate($request);
    }
}
