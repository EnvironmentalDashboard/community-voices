<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\HasId;

class TagCollection extends GroupCollection
{
    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Tag;
    }

    public function __construct()
    {
        $this->groupType = self::GROUP_TYPE_TAG;
    }

    public function toArray()
    {
        return ['tagCollection' => parent::toArray()['groupCollection']];
    }
}
