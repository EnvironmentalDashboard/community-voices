<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Identification
{
    public static function getIdentity($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function postLogin($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function postLogout($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_UNVERIFIED);
    }
}
