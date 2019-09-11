<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\App\Api\Component\AccessControlHelper;
use CommunityVoices\Model\Entity;

class Slide
{
    public static function getAllSlide($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getSlide($user, $arguments, $stateObserver = null)
    {
        return ($user->isRoleAtLeast(Entity\User::ROLE_GUEST) && AccessControlHelper::isApprovedMedia($stateObserver, 'slideLookup', 'slide'))
            || $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function getSlideUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postSlideUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function getSlideUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }


    public static function postSlideUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postSlideDelete($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
