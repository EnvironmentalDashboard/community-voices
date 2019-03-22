<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Quote
{
    protected $recognitionAdapter;
    protected $quoteAPIController;
    protected $tagAPIController;
    protected $contentCategoryAPIController;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\Controller\Quote $quoteAPIController,
        Api\Controller\Tag $tagAPIController,
        Api\Controller\ContentCategory $contentCategoryAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->quoteAPIController = $quoteAPIController;
        $this->tagAPIController = $tagAPIController;
        $this->contentCategoryAPIController = $contentCategoryAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getQuote($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        /**
         * Direct requests for various components page requires
         */

        $apiController->getQuote($request);
        $apiController->getBoundaryQuotes($request);
    }

    public function getAllQuote($request)
    {
        $quoteAPIController = $this->secureContainer->contain($this->quoteAPIController);

        // [example] filter by creator IDs
        // $request->attributes->set('creatorIDs', [1, 3 ,4 ,5 ,6]);

        // [example] filter by status
        // $request->attributes->set('status', ['rejected', 'pending']);

        $identity = $this->recognitionAdapter->identify();

        $quoteAPIController->getAllQuote($request, $identity);
    }

    public function getQuoteUpload($request)
    {
        $quoteAPIController = $this->secureContainer->contain($this->quoteAPIController);
        $tagAPIController = $this->secureContainer->contain($this->tagAPIController);
        $contentCategoryAPIController = $this->secureContainer->contain($this->contentCategoryAPIController);

        $quoteAPIController->getQuoteUpload($request);
        $tagAPIController->getAllTag($request);
        $contentCategoryAPIController->getAllContentCategory($request);
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

    public function postQuoteDelete($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->postQuoteDelete($request);
    }

    public function postQuoteUnpair($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->postQuoteUnpair($request);
    }
}
