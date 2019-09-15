<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;
use CommunityVoices\Model\Entity;

class Slide extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function getAllSlide()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getSlide()
    {
        return ($this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST) && $this->isApprovedMedia('slideLookup', 'slide'))
            || $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getSlideUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getSlideUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideDelete()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
