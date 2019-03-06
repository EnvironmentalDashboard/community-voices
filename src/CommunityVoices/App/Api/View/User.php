<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;

class User
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function postUser()
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries('registration'))
            ? $clientStateObserver->getEntriesBySubject('registration') : [];

        $response = new HttpFoundation\JsonResponse(['errors' => $errors]);

        return $response;
    }

    public function getUser()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('userLookup');
        $user = $stateObserver->getEntry('user')[0];

        $response = new HttpFoundation\JsonResponse($user->toArray());

        return $response;
    }
}
