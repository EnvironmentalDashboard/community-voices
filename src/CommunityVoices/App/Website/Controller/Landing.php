<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Api;

class Landing
{
    protected $landingAPIController;

    public function __construct(
        Api\Controller\Landing $landingAPIController,
        Api\Controller\ContentCategory $contentCategoryAPIController
    ) {
        $this->landingAPIController = $landingAPIController;
        $this->contentCategoryAPIController = $contentCategoryAPIController;
    }

    public function getLanding($request)
    {
        $this->landingAPIController->getLanding($request);
        $this->contentCategoryAPIController->getAllContentCategory($request);
    }
}
