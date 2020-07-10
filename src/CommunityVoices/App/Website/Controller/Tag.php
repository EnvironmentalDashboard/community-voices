<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;

class Tag
{
    //protected $tagAPIController;
    protected $apiProvider;

    public function __construct(
        //Api\Controller\Tag $tagAPIController
        Component\ApiProvider $apiProvider
    ) {
        //$this->tagAPIController = $tagAPIController;
        $this->apiProvider = $apiProvider;
    }

    public function getAllTag($request)
    {
        //$this->tagAPIController->getAllTag($request);
    }

    public function postTagUpload($request)
    {
        //$this->tagAPIController->postTagUpload($request);
        $errors = $this->apiProvider->postJson('/tags/new', $request);
        return $errors;
    }
}
