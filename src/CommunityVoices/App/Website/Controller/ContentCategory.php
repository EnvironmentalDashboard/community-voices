<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;

class ContentCategory
{
    //protected $contentCategoryAPIController;
    protected $apiProvider;

    public function __construct(
        //Api\Controller\ContentCategory $contentCategoryAPIController
        Component\ApiProvider $apiProvider
    ) {
        //$this->contentCategoryAPIController = $contentCategoryAPIController;
        $this->apiProvider = $apiProvider;
    }

    public function getAllContentCategory($request)
    {
        //$this->contentCategoryAPIController->getAllContentCategory($request);
    }

    public function getContentCategory($request)
    {
        //$this->contentCategoryAPIController->getContentCategory($request);
    }

    public function getContentCategoryUpload($request)
    {
        //$this->contentCategoryAPIController->getContentCategoryUpload($request);
    }

    public function postContentCategoryUpload($request)
    {
        //$this->contentCategoryAPIController->postContentCategoryUpload($request);
    }

    public function getContentCategoryUpdate($request)
    {
        //$this->contentCategoryAPIController->getContentCategoryUpdate($request);
    }

    public function postContentCategoryUpdate($request)
    {
        //$this->contentCategoryAPIController->postContentCategoryUpdate($request);
    }

    public function postContentCategoryDelete($request)
    {
        //$this->contentCategoryAPIController->postContentCategoryDelete($request);

        $id = $request->attributes->get('groupId');
        $errors = $this->apiProvider->postJson("/content-categories/{$id}/delete", $request);
        return $errors;
    }
}
