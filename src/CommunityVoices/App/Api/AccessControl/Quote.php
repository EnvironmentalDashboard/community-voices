<?php

namespace CommunityVoices\App\Api\AccessControl;

use CommunityVoices\Model\Entity;

class Quote
{
    public static function getQuote($user, $arguments, $injector)
    {
        $quoteLookup = $injector->make("CommunityVoices\\Model\\Service\\QuoteLookup");
        $quoteLookup->findById($arguments[0]->attributes->get('id'));

        $stateObserver = $injector->make("CommunityVoices\\Model\\Component\\StateObserver");
        $stateObserver->setSubject('quoteLookup');
        $quote = $stateObserver->getEntry('quote')[0];

        return ($user->isRoleAtLeast(Entity\User::ROLE_GUEST) && $quote->getStatus() === Entity\Media::STATUS_APPROVED)
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
