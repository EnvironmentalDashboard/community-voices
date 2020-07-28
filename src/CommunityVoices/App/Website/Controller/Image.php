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

    public function postImageUpload($request)
    {
        //$this->imageAPIController->postImageUpload($request);

        $errors = $this->apiProvider->postJson('/images/new/authenticate', $request);
        return $errors;
    }
    
    public function postImageDelete($request)
    {
        //$this->imageAPIController->postImageDelete($request);
    }

    public function postImageUnpair($request)
    {
        //$this->imageAPIController->postImageUnpair($request);
        $image = $request->attributes->get('image');
        $slide = $request->attributes->get('slide');
        $errors = $this->apiProvider->postJson("/images/{$image}/unpair/{$slide}", $request);
        return $errors;
    }
}
