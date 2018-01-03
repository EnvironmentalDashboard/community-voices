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

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\View\Quote $quoteAPIView,
        Component\MapperFactory $mapperFactory

    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->quoteAPIView = $quoteAPIView;
        $this->mapperFactory = $mapperFactory;
    }

    public function getQuote()
    {
        $apiResponse = $this->quoteAPIView->getQuote();

        $response = new Response($apiResponse->getContent());

        $this->finalize($response);
        return $response;
    }
}
