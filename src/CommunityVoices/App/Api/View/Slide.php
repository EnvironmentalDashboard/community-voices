<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Slide
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getAllSlide()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideFindAll');
        $slideCollection = $stateObserver->getEntry('slideCollection')[0];

        $response = new HttpFoundation\JsonResponse($slideCollection->toArray());

        return $response;
    }

    public function getSlide()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideLookup');
        $slide = $stateObserver->getEntry('slide')[0];

        $response = new HttpFoundation\JsonResponse($slide->toArray());

        return $response;
    }

    public function getSlideUpload()
    {
        // intentionally blank
    }

    public function postSlideUpload()
    {
        // intentionally blank
    }
}
