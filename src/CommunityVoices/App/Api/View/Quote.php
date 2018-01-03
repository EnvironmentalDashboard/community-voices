<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;

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
}
