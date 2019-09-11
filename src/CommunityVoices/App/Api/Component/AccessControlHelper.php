<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\Model\Entity;

class AccessControlHelper
{
    public static function isApprovedMedia($stateObserver, $subject, $entry)
    {
        if ($stateObserver) {
            $stateObserver->setSubject($subject);
            $entity = $stateObserver->getEntry($entry)[0];
        }

        return $entity ? $entity->getStatus() === Entity\Media::STATUS_APPROVED : true;
    }
}
