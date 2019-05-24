<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ContentCategory extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory,
        Component\SecureContainer $secureContainer
    ) {
        parent::__construct($mapperFactory, $secureContainer);
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
