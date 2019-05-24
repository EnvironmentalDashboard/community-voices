<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Landing extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory,
        Component\SecureContainer $secureContainer
    ) {
        parent::__construct($mapperFactory, $secureContainer);
    }

    protected function getLanding()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('slideFindAll');
        $slideCollection = $stateObserver->getEntry('slideCollection')[0];
        $response = new HttpFoundation\JsonResponse($slideCollection->toArray());
        return $response;
    }
}
