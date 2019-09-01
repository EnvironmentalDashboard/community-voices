<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Tag
{
    public static function getAllTag($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }
}
