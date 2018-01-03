<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;
use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation\Response;

class Quote extends Component\View
{
    protected $recognitionAdapter;
    protected $quoteAPIView;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\View\Quote $quoteAPIView,
        Component\MapperFactory $mapperFactory,
        Api\Component\SecureContainer $secureContainer

    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->quoteAPIView = $quoteAPIView;
        $this->mapperFactory = $mapperFactory;
        $this->secureContainer = $secureContainer;
    }

    public function getQuote()
    {
        $apiView = $this->secureContainer->contain($this->quoteAPIView);

        $apiResponse = $apiView->getQuote();

        $response = new Response($apiResponse->getContent());

        $this->finalize($response);
        return $response;
    }
}
