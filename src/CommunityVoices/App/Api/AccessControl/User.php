<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class User
{
    public static function postRegistration($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getUser($user, $arguments)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN)
            || $user->getId() == $arguments[0]->attributes->get('id');
    }

    public static function postUser($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public static function getAllUser($user, $arguments)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public static function newToken($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }
}
