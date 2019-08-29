<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Image extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory
    ) {
        parent::__construct($mapperFactory);
    }

    public function sendImage()
    {
        // wut
    }

    public function getImage()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('imageLookup');
        $image = $stateObserver->getEntry('image')[0];
        // var_dump($image->toArray()['image']['tagCollection']['groupCollection']);die;

        $response = new HttpFoundation\JsonResponse($image->toArray());

        return $response;
    }

    public function getAllImage()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('imageFindAll');
        $imageCollection = $stateObserver->getEntry('imageCollection')[0]->toArray();
        $imageCollection['imageCollectionPhotographers'] = $stateObserver->getEntry('imageCollectionPhotographers')[0];
        $imageCollection['imageCollectionOrgs'] = $stateObserver->getEntry('imageCollectionOrgs')[0];

        $stateObserver->setSubject('tagLookup');
        $imageCollection['tags'] = $stateObserver->getEntry('tag')[0]->toArray();

        $response = new HttpFoundation\JsonResponse($imageCollection);

        return $response;
    }

    public function getImageUpload()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('tagLookup');
        $tag = $stateObserver->getEntry('tag')[0];

        $response = new HttpFoundation\JsonResponse($tag->toArray());

        return $response;
    }

    public function postImageUpload()
    {
        // intentionally blank
    }
}
