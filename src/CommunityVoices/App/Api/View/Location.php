<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;

class Location
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getAllLocation()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject("locationLookup");
        $locationCollection = $stateObserver->getEntry("locationCollection")[0]->toArray();

        $response = new HttpFoundation\JsonResponse($locationCollection);

        return $response;
    }
}
