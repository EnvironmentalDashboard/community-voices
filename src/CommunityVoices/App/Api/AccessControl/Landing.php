<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Landing
{
    public static function getLanding($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }
}
