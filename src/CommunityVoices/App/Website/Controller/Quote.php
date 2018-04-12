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

        // [example] filter by creator IDs
        // $request->attributes->set('creatorIDs', [1, 3 ,4 ,5 ,6]);

        // [example] filter by status
        // $request->attributes->set('status', ['rejected', 'pending']);

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

    public function getQuoteUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->getQuote($request);
    }

    public function postQuoteUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->postQuoteUpdate($request);
    }
}
