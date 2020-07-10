<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class Tag extends Group
{
    public function __construct()
    {
        $this->type = self::TYPE_TAG;
    }

    private $mediaId;
    private $groupId;

    public function setMediaId(int $id)
    {
        $this->mediaId = $id;
    }

    public function getMediaId()
    {
        return $this->mediaId;
    }

    public function setGroupId(?int $id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        // This checks for label being present.
        return parent::validateForUpload($stateObserver);
    }

    public function toArray()
    {
        return ['tag' => parent::toArray()['group']];
    }
}
