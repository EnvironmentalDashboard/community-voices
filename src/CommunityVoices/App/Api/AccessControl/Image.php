<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class Image extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public function sendImage()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getImage()
    {
        return ($this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST) /*&& AccessControlHelper::isApprovedMedia($stateObserver, 'imageLookup', 'image')*/)
            || $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getAllImage()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getImageUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public function postImageUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public function getImageUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postImageUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postImageDelete()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postImageUnpair()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
