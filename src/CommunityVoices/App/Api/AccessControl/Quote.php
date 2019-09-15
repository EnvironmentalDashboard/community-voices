<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\Contract;
use CommunityVoices\App\Api\Component\AccessController;
use CommunityVoices\App\Api\Controller;

class Quote extends AccessController
{
    public function __construct(
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver
    ) {
        parent::__construct($identifier, $logger, $stateObserver);
    }

    public function getQuote()
    {
        return ($this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST) && $this->isApprovedMedia('quoteLookup', 'quote'))
            || $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getBoundaryQuotes()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getAllQuote()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getQuoteUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postQuoteUpload()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getQuoteUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postQuoteUpdate()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postQuoteDelete()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public function postQuoteUnpair()
    {
        return $this->getUserEntity()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
