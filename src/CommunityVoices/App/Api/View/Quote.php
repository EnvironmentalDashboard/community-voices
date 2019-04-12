<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Quote
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $quote = $stateObserver->getEntry('quote')[0];

        $response = new HttpFoundation\JsonResponse($quote->toArray());

        return $response;
    }

    public function getBoundaryQuotes()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $quotes = $stateObserver->getEntry('boundaryQuotes')[0];

        $response = new HttpFoundation\JsonResponse($quotes->toArray());

        return $response;
    }

    public function getAllQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteFindAll');
        $quoteCollection = $stateObserver->getEntry('quoteCollection')[0]->toArray();
        $quoteCollection['quoteCollectionAttributions'] = $stateObserver->getEntry('quoteCollectionAttributions')[0];
        $quoteCollection['quoteCollectionSubAttributions'] = $stateObserver->getEntry('quoteCollectionSubAttributions')[0];

        $stateObserver->setSubject('tagLookup');
        $quoteCollection['tags'] = $stateObserver->getEntry('tag')[0]->toArray();

        $stateObserver->setSubject('contentCategoryLookup');
        $quoteCollection['contentCategories'] = $stateObserver->getEntry('contentCategory')[0]->toArray();

        $response = new HttpFoundation\JsonResponse($quoteCollection);

        return $response;
    }

    public function getQuoteUpload()
    {
        // intentionally blank
    }

    public function postQuoteUpload()
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        // In the case that we have retrieved errors, we will send them along.
        // Otherwise, our errors array will be an empty array.
        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('quoteUploadErrors'))
            ? $clientStateObserver->getEntriesBySubject('quoteUploadErrors') : [];

        $id = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('quoteUpload'))
            ? $clientStateObserver->getEntriesBySubject('quoteUpload') : [];

        $combined = ['upload' => ['errors' => $errors, 'quote' => $id]];
        $response = new HttpFoundation\JsonResponse($combined);

        return $response;
    }

    public function getQuoteUpdate()
    {
        // intentionally blank
    }

    public function postQuoteUpdate()
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        // In the case that we have retrieved errors, we will send them along.
        // Otherwise, our errors array will be an empty array.
        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('quoteUpdate'))
            ? $clientStateObserver->getEntriesBySubject('quoteUpdate') : [];

        $response = new HttpFoundation\JsonResponse(['errors' => $errors]);

        return $response;
    }
}
