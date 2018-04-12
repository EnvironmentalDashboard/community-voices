<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Image
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getImage()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('imageLookup');
        $image = $stateObserver->getEntry('image')[0];

        $response = new HttpFoundation\JsonResponse($image->toArray());

        return $response;
    }

    public function getAllImage()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('imageFindAll');
        $imageCollection = $stateObserver->getEntry('imageCollection')[0];

        $response = new HttpFoundation\JsonResponse($imageCollection->toArray());

        return $response;
    }

    public function getImageUpload()
    {
        // intentionally blank
    }

    public function postImageUpload()
    {
        // intentionally blank
    }
}
