<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ContentCategory extends Component\View
{
    protected $mapperFactory;

    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer);

        $this->mapperFactory = $mapperFactory;
    }

    protected function getAllContentCategory()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('contentCategoryLookup');
        $contentCategories = $stateObserver->getEntry('contentCategory')[0];

        $response = new HttpFoundation\JsonResponse($contentCategories->toArray());

        return $response;
    }
}
