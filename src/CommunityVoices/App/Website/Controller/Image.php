<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Image
{
    protected $imageAPIController;

    public function __construct(
        Api\Controller\Image $imageAPIController
    ) {
        $this->imageAPIController = $imageAPIController;
    }

    public function sendImage($request)
    {
        $this->imageAPIController->sendImage($request);
    }

    public function getImage($request)
    {
        $this->imageAPIController->getImage($request);
    }

    public function getAllImage($request)
    {
        $this->imageAPIController->getAllImage($request);
    }

    public function getImageUpload($request)
    {
        $this->imageAPIController->getImageUpload($request);
    }

    public function postImageUpload($request)
    {
        $this->imageAPIController->postImageUpload($request);
    }

    public function getImageUpdate($request)
    {
        $this->imageAPIController->getImage($request);
    }

    public function postImageUpdate($request)
    {
        $this->imageAPIController->postImageUpdate($request);
    }

    public function postImageDelete($request)
    {
        $this->imageAPIController->postImageDelete($request);
    }

    public function postImageUnpair($request)
    {
        $this->imageAPIController->postImageUnpair($request);
    }
}
