<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class ContentCategory
{
    protected $contentCategoryAPIController;

    public function __construct(
        Api\Controller\ContentCategory $contentCategoryAPIController
    ) {
        $this->contentCategoryAPIController = $contentCategoryAPIController;
    }

    public function getAllContentCategory($request)
    {
        $this->contentCategoryAPIController->getAllContentCategory($request);
    }

    public function getContentCategory($request)
    {
        $this->contentCategoryAPIController->getContentCategory($request);
    }

    public function getContentCategoryUpload($request)
    {
        $this->contentCategoryAPIController->getContentCategoryUpload($request);
    }

    public function postContentCategoryUpload($request)
    {
        $this->contentCategoryAPIController->postContentCategoryUpload($request);
    }

    public function getContentCategoryUpdate($request)
    {
        $this->contentCategoryAPIController->getContentCategoryUpdate($request);
    }

    public function postContentCategoryUpdate($request)
    {
        $this->contentCategoryAPIController->postContentCategoryUpdate($request);
    }
}
