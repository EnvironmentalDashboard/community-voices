<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Landing
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getLanding()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideFindAll');
        $slideCollection = $stateObserver->getEntry('slideCollection')[0];
        $response = new HttpFoundation\JsonResponse($slideCollection->toArray());
        return $response;
    }
}
