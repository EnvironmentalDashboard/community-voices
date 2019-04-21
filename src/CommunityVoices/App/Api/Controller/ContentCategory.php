<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\ContentCategoryLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class ContentCategory extends Component\Controller
{
    protected $contentCategoryLookup;

    public function __construct(
        Service\ContentCategoryLookup $contentCategoryLookup
    ) {
        $this->contentCategoryLookup = $contentCategoryLookup;
    }

    public function getAllContentCategory()
    {
        $this->contentCategoryLookup->findAll();
    }
}
