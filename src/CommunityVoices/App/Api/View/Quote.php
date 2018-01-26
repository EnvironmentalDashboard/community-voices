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

    public function getQuoteUpload()
    {
        // intentionally blank
    }

    public function postQuote()
    {
      $clientStateMapper = $this->mapperFactory->createClientStateMapper();
      $clientStateObserver = $clientStateMapper->retrieve();

      $response = new Response(
          !($clientStateObserver && $clientStateObserver->hasEntries())
      );

      return $response;
    }
}
