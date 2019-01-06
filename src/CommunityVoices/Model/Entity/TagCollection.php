<?php

namespace CommunityVoices\Model\Entity;

class TagCollection extends GroupCollection
{
    public function __construct()
    {
        $this->groupType = self::GROUP_TYPE_TAG;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Tag;
    }
}
