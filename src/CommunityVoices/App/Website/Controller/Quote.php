<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Quote
{
    protected $recognitionAdapter;
    protected $mapperFactory;
    protected $quoteAPIController;
    protected $tagAPIController;
    protected $contentCategoryAPIController;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Api\Controller\Quote $quoteAPIController,
        Api\Controller\Tag $tagAPIController,
        Api\Controller\ContentCategory $contentCategoryAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
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
        $tagAPIController = $this->secureContainer->contain($this->tagAPIController);
        $contentCategoryAPIController = $this->secureContainer->contain($this->contentCategoryAPIController);

        // [example] filter by creator IDs
        // $request->attributes->set('creatorIDs', [1, 3 ,4 ,5 ,6]);

        // [example] filter by status
        // $request->attributes->set('status', ['rejected', 'pending']);

        $identity = $this->recognitionAdapter->identify();

        $quoteAPIController->getAllQuote($request, $identity);
        $tagAPIController->getAllTag($request);
        $contentCategoryAPIController->getAllContentCategory($request);
    }

    /*
     * We need all of the tags in our database
     * and all of the content categories to provide
     * as checkbox options.
     */
    public function getQuoteUpload($request)
    {
        $tagAPIController = $this->secureContainer->contain($this->tagAPIController);
        $contentCategoryAPIController = $this->secureContainer->contain($this->contentCategoryAPIController);

        $tagAPIController->getAllTag($request);
        $contentCategoryAPIController->getAllContentCategory($request);
    }

    public function postQuoteUpload($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);
        $identity = $this->recognitionAdapter->identify();

        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $status = $request->request->get('status');
        $tags = $request->request->get('tags') ?? [];
        $contentCategories = $request->request->get('contentCategories') ?? [];

        $form = [
            'text' => $text,
            'attribution' => $attribution,
            'subAttribution' => $subAttribution,
            'dateRecorded' => $dateRecorded,
            'status' => $status,
            'tags' => $tags,
            'contentCategories' => $contentCategories
        ];

        $formCache = new Component\CachedItem('quoteUploadForm');
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);

        if (!$apiController->postQuoteUpload($request, $identity)) {
            $this->getQuoteUpload($request);
        }
    }

    public function getQuoteUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);
        $tagAPIController = $this->secureContainer->contain($this->tagAPIController);
        $contentCategoryAPIController = $this->secureContainer->contain($this->contentCategoryAPIController);

        $apiController->getQuote($request);
        $tagAPIController->getAllTag($request);
        $contentCategoryAPIController->getAllContentCategory($request);
    }

    public function postQuoteUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $status = $request->request->get('status');
        $tags = $request->request->get('tags') ?? [];
        $contentCategories = $request->request->get('contentCategories') ?? [];

        $form = [
            'text' => $text,
            'attribution' => $attribution,
            'subAttribution' => $subAttribution,
            'dateRecorded' => $dateRecorded,
            'status' => $status,
            'tags' => $tags,
            'contentCategories' => $contentCategories
        ];

        $formCache = new Component\CachedItem('quoteUpdateForm');
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);

        if (!$apiController->postQuoteUpdate($request)) {
            $this->getQuoteUpdate($request);
        };
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
