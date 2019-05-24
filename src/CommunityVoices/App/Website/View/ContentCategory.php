<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;

class ContentCategory extends Component\View
{
    protected $contentCategoryAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView,
        Api\View\ContentCategory $contentCategoryAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $identificationAPIView);

        $this->contentCategoryAPIView = $contentCategoryAPIView;
    }

    public function getAllContentCategory($request)
    {
        // TODO: write this
    }
}
