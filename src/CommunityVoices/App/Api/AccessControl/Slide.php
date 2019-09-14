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
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public function getSlide()
    {
        return ($this->getUser()->isRoleAtLeast(Entity\User::ROLE_GUEST)/* && AccessControlHelper::isApprovedMedia($stateObserver, 'slideLookup', 'slide')*/)
            || $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getSlideUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideUpload()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function getSlideUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideUpdate()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public function postSlideDelete()
    {
        return $this->getUser()->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
