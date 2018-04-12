<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation\Response;

class User
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function postUser($response)
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        $response = new Response(
            !($clientStateObserver && $clientStateObserver->hasEntries())
        );

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
