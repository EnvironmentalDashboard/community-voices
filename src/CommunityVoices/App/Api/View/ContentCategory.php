<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ContentCategory extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory
    ) {
        parent::__construct($mapperFactory);
    }

    public function getAllContentCategory()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('contentCategoryLookup');
        $contentCategories = $stateObserver->getEntry('contentCategory')[0];

        $response = new HttpFoundation\JsonResponse($contentCategories->toArray());

        return $response;
    }

    public function getContentCategory()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('contentCategoryLookup');
        $contentCategory = $stateObserver->getEntry('contentCategory')[0];

        $response = new HttpFoundation\JsonResponse($contentCategory->toArray());

        return $response;
    }

    public function getContentCategoryUpload()
    {
        // intentionally blank
    }

    public function postContentCategoryUpload()
    {
        return $this->errorsResponse('contentCategoryUpload');
    }

    public function getContentCategoryUpdate()
    {
        // intentionally blank
    }

    public function postContentCategoryUpdate()
    {
        return $this->errorsResponse('contentCategoryUpdate');
    }

    public function postContentCategoryDelete()
    {
        // intentionally blank
    }
}
