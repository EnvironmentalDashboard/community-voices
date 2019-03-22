<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ContentCategory
{
    protected $mapperFactory;

    public function __construct(
        MapperFactory $mapperFactory
    ) {
        $this->mapperFactory = $mapperFactory;
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
}
