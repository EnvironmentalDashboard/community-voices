<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;

class Location extends Component\View
{
    protected $mapperFactory;

    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer);

        $this->mapperFactory = $mapperFactory;
    }

    protected function getAllLocation()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject("locationLookup");
        $locationCollection = $stateObserver->getEntry("locationCollection")[0]->toArray();

        $response = new HttpFoundation\Response();

        return $response;
    }
}
