<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class User extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public function postRegistration()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getUser($arguments)
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN)
            || $this->getUser()->getId() == $arguments[0]->attributes->get('id');
    }

    public function postUser()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function getAllUser()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function newToken()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }
}
