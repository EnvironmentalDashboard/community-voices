<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Quote
{
    protected $recognitionAdapter;
    protected $quoteAPIController;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\Controller\Quote $quoteAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->quoteAPIController = $quoteAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getQuote($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->getQuote($request);
    }

    public function getAllQuote($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->getAllQuote($request);
    }

    public function getQuoteUpload()
    {
        // intentionally blank
    }

    public function postQuoteUpload($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);
        $identity = $this->recognitionAdapter->identify();

        $apiController->postQuoteUpload($request, $identity);
    }

    public function getQuoteUpdate()
    {
        // intentionally blank
    }

    public function postQuoteUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);
        $identity = $this->recognitionAdapter->identify();

        $apiController->postQuoteUpdate($request);
    }
}
