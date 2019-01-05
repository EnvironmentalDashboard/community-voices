<?php

namespace CommunityVoices\Model\Entity;

class TagCollection extends GroupCollection
{
    public function __construct()
    {
        $this->type = self::TYPE_TAG;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Tag;
    }
}
