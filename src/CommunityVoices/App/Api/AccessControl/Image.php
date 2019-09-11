<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component\AccessControlHelper;

class Image
{
    public static function sendImage($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getImage($user, $arguments, $stateObserver = null)
    {
        return ($user->isRoleAtLeast(Entity\User::ROLE_GUEST) && AccessControlHelper::isApprovedMedia($stateObserver, 'imageLookup', 'image'))
            || $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function getAllImage($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getImageUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public static function postImageUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_USER);
    }

    public static function getImageUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postImageUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postImageDelete($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public static function postImageUnpair($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
