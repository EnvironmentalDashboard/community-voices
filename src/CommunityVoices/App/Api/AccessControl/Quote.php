<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Quote
{
    public static function getQuote($user, $arguments, $stateObserver)
    {
        $stateObserver->setSubject('quoteLookup');
        var_dump($stateObserver);
        $quote = $stateObserver->getEntry('quote')[0];

        $guestPermission = $quote ? $quote->getStatus() === Entity\Media::STATUS_APPROVED : true;

        return ($user->isRoleAtLeast(Entity\User::ROLE_GUEST) && $guestPermission)
            || $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function getBoundaryQuotes($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getAllQuote($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    public static function getQuoteUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postQuoteUpload($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function getQuoteUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postQuoteUpdate($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_MANAGER);
    }

    public static function postQuoteDelete($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }

    public static function postQuoteUnpair($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_ADMIN);
    }
}
