<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class ContentCategory extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public function getAllContentCategory()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getContentCategory()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getContentCategoryUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function getContentCategoryUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postContentCategoryDelete()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function getAllContentCategoryFromNavbar()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }
}
