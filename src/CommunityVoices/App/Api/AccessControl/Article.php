<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;

class Article extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public function getArticle()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getAllArticle()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getArticleUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postArticleUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getArticleUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postArticleUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function searchByStatus()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }
}
