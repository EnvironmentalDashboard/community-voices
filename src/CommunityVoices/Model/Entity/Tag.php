<?php

namespace CommunityVoices\Model\Entity;

class Tag extends Group
{
    public function __construct()
    {
        $this->type = self::TYPE_TAG;
    }

    private $mediaId;
    private $groupId;

    public function setMediaId(int $id) {
    	$this->mediaId = $id;
    }

    public function getMediaId() {
    	return $this->mediaId;
    }

    public function setGroupId(int $id) {
    	$this->groupId = $id;
    }

    public function getGroupId() {
    	return $this->groupId;
    }

    public function toArray()
    {
        return ['tag' => array_merge(parent::toArray()['group'], [
            'mediaId' => $this->mediaId,
            'groupId' => $this->groupId
        ])];
    }
}
