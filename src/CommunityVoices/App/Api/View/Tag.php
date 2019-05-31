<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Tag extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer, $mapperFactory);
    }

    protected function getAllTag()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('tagLookup');
        $tags = $stateObserver->getEntry('tag')[0];

        $response = new HttpFoundation\JsonResponse($tags->toArray());

        return $response;
    }
}
