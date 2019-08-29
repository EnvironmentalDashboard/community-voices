<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Quote
{
    protected $mapperFactory;
    protected $quoteAPIController;
    protected $tagAPIController;
    protected $contentCategoryAPIController;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Api\Controller\Quote $quoteAPIController,
        Api\Controller\Tag $tagAPIController,
        Api\Controller\ContentCategory $contentCategoryAPIController
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->quoteAPIController = $quoteAPIController;
        $this->tagAPIController = $tagAPIController;
        $this->contentCategoryAPIController = $contentCategoryAPIController;
    }

    public function getQuote($request)
    {
        /**
         * Direct requests for various components page requires
         */

        $this->quoteAPIController->getQuote($request);
        $this->quoteAPIController->getBoundaryQuotes($request);
    }

    public function getAllQuote($request)
    {
        // [example] filter by creator IDs
        // $request->attributes->set('creatorIDs', [1, 3 ,4 ,5 ,6]);

        // [example] filter by status
        // $request->attributes->set('status', ['rejected', 'pending']);

        $this->quoteAPIController->getAllQuote($request);
        $this->tagAPIController->getAllTag($request);
        $this->contentCategoryAPIController->getAllContentCategory($request);
    }

    /*
     * We need all of the tags in our database
     * and all of the content categories to provide
     * as checkbox options.
     */
    public function getQuoteUpload($request)
    {
        $this->tagAPIController->getAllTag($request);
        $this->contentCategoryAPIController->getAllContentCategory($request);
    }

    public function postQuoteUpload($request)
    {
        $this->saveQuoteForm($request, 'quoteUploadForm');

        if (!$this->quoteAPIController->postQuoteUpload($request)) {
            $this->getQuoteUpload($request);
        }
    }

    public function getQuoteUpdate($request)
    {
        $this->quoteAPIController->getQuote($request);
        $this->tagAPIController->getAllTag($request);
        $this->contentCategoryAPIController->getAllContentCategory($request);
    }

    public function postQuoteUpdate($request)
    {
        $this->saveQuoteForm($request, 'quoteUpdateForm');

        if (!$this->quoteAPIController->postQuoteUpdate($request)) {
            $this->getQuoteUpdate($request);
        };
    }

    public function postQuoteDelete($request)
    {
        $this->quoteAPIController->postQuoteDelete($request);
    }

    public function postQuoteUnpair($request)
    {
        $this->quoteAPIController->postQuoteUnpair($request);
    }

    // ------
    private function saveQuoteForm($request, $cacheName)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $quotationMarks = $request->request->get('quotationMarks') === 'on';
        $dateRecorded = $request->request->get('dateRecorded');
        $status = $request->request->get('status') === 'on' ? Entity\Media::STATUS_APPROVED : Entity\Media::STATUS_PENDING;
        $tags = $request->request->get('tags') ?? [];
        $contentCategories = $request->request->get('contentCategories') ?? [];

        $form = [
            'text' => $text,
            'attribution' => $attribution,
            'subAttribution' => $subAttribution,
            'quotationMarks' => $quotationMarks,
            'dateRecorded' => $dateRecorded,
            'status' => $status,
            'tags' => $tags,
            'contentCategories' => $contentCategories
        ];

        $formCache = new Component\CachedItem($cacheName);
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);
    }
}
