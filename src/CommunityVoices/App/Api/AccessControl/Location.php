<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Location
{
    public static function getAllLocation($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }
}
