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
}
