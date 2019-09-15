<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class ContentCategory extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function getAllContentCategory()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getContentCategory()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getContentCategoryUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function getContentCategoryUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryDelete()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function getAllContentCategoryFromNavbar()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }
}
