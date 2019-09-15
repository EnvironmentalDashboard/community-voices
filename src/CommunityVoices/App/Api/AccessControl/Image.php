<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class Image extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function sendImage()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getImage()
    {
        return ($this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST) && $this->isApprovedMedia('imageLookup', 'image'))
            || $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getAllImage()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getImageUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public function postImageUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public function getImageUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postImageUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postImageDelete()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postImageUnpair()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
