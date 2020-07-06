<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;

class Image
{
    //protected $imageAPIController;
    protected $apiProvider;

    public function __construct(
        //Api\Controller\Image $imageAPIController
        Component\ApiProvider $apiProvider
    ) {
        //$this->imageAPIController = $imageAPIController;
        $this->apiProvider = $apiProvider;
    }

    public function sendImage($request)
    {
        //$this->imageAPIController->sendImage($request);
    }

    public function getImage($request)
    {
        //$this->imageAPIController->getImage($request);
    }

    public function getAllImage($request)
    {
        //$this->imageAPIController->getAllImage($request);
    }

    public function getImageUpload($request)
    {
        //$this->imageAPIController->getImageUpload($request);
    }

    public function postImageUpload($request)
    {
        //$this->imageAPIController->postImageUpload($request);

        $errors = $this->apiProvider->postJson('/images/new/authenticate', $request);
        return $errors;
    }

    public function getImageUpdate($request)
    {
        //$this->imageAPIController->getImage($request);
    }

    // I had originally thought this was only through AJAX, but it is instead only
    // mostly through AJAX.
    public function postImageUpdate($request)
    {
        //$this->imageAPIController->postImageUpdate($request);
    }

    // Is this ever accessed not through AJAX?
    public function postImageDelete($request)
    {
        //$this->imageAPIController->postImageDelete($request);
    }

    public function postImageUnpair($request)
    {
        //$this->imageAPIController->postImageUnpair($request);
    }
}
