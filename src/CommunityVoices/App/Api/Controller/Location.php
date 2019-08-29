<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Location extends Component\Controller
{
    protected $locationLookup;

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,

        Service\LocationLookup $locationLookup
    ) {
        parent::__construct($identifier, $logger);

        $this->locationLookup = $locationLookup;
    }

    protected function CANgetAllLocation($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    protected function getAllLocation($request)
    {
        $this->locationLookup->findAll2();
    }
}
