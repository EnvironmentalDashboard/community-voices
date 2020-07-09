<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Quote extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer, $mapperFactory);
    }

    protected function getQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $quote = $stateObserver->getEntry('quote')[0];

        $response = new HttpFoundation\JsonResponse($quote->toArray());

        return $response;
    }

    protected function getBoundaryQuotes()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $quotes = $stateObserver->getEntry('boundaryQuotes')[0];

        $response = new HttpFoundation\JsonResponse($quotes->toArray());

        return $response;
    }

    protected function getAllQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteFindAll');
        $quoteCollection = $stateObserver->getEntry('quoteCollection')[0]->toArray();
        $quoteCollection['quoteCollectionAttributions'] = $stateObserver->getEntry('quoteCollectionAttributions')[0];
        $quoteCollection['quoteCollectionSubAttributions'] = $stateObserver->getEntry('quoteCollectionSubAttributions')[0];

        $response = new HttpFoundation\JsonResponse($quoteCollection);

        return $response;
    }

    protected function getQuoteUpload()
    {
        // intentionally blank
    }

    protected function postQuoteUpload()
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        // In the case that we have retrieved errors, we will send them along.
        // Otherwise, our errors array will be an empty array.
        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('quoteFormErrors'))
            ? $clientStateObserver->getEntriesBySubject('quoteFormErrors') : [];

        $id = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('quoteUpload'))
            ? $clientStateObserver->getEntriesBySubject('quoteUpload') : [];

        $combined = ['upload' => ['errors' => $errors, 'quote' => $id]];
        $response = new HttpFoundation\JsonResponse($combined);

        return $response;
    }

    protected function postBatch($request)
    {
        $filePath = $request->files->get('file')[0]->getPathname();
        // Open the file for reading
        if (($h = fopen($filePath, "r")) !== FALSE)
        {
          // Convert each line into the local $data variable
          while (($data = fgetcsv($h, 1000, ",")) !== FALSE)
          {
            var_dump($data);
          }

          // Close the file
          fclose($h);
        }
        // Display the code in a readable format
    }

    protected function getQuoteUpdate()
    {
        // intentionally blank
    }

    protected function postQuoteUpdate()
    {
        return $this->errorsResponse("quoteFormErrors");
    }

    protected function getQuoteRelatedSlide()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $slides = $stateObserver->getEntry('relatedSlide')[0];

        $response = new HttpFoundation\JsonResponse($slides);

        return $response;
    }

    protected function getQuotePrevQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $slides = $stateObserver->getEntry('prevQuote')[0];

        $response = new HttpFoundation\JsonResponse($slides);

        return $response;
    }

    protected function getQuoteNextQuote()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('quoteLookup');
        $slides = $stateObserver->getEntry('nextQuote')[0];

        $response = new HttpFoundation\JsonResponse($slides);

        return $response;
    }
}
