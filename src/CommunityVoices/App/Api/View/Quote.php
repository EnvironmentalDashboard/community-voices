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

    public function getAllQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteFindAll');
        $quoteCollection = $stateObserver->getEntry('quoteCollection')[0];

        $response = new HttpFoundation\JsonResponse($quoteCollection->toArray());

        return $response;
    }

    public function getQuoteUpload()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('tagLookup');
        $tag = $stateObserver->getEntry('tag')[0];

        $response = new HttpFoundation\JsonResponse($tag->toArray());

        return $response;
    }

    public function postQuoteUpload()
    {
        // intentionally blank
    }

    public function getQuoteUpdate()
    {
        // intentionally blank
    }

    public function postQuoteUpdate()
    {
        // intentionally blank
    }
}
