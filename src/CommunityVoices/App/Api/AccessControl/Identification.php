<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class Identification extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function getIdentity()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function postLogin()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function postLogout()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_UNVERIFIED);
    }
}
