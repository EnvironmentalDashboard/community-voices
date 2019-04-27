<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\ContentCategoryLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class ContentCategory extends Component\Controller
{
    protected $secureContainer;
    protected $contentCategoryLookup;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Service\ContentCategoryLookup $contentCategoryLookup
    ) {
        parent::__construct($secureContainer);

        $this->contentCategoryLookup = $contentCategoryLookup;
    }

    private function getAllContentCategory()
    {
        $this->contentCategoryLookup->findAll();
    }
}
