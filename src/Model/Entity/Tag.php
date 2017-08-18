<?php

namespace CommunityVoices\Model\Entity;

class Tag extends Group
{
    public function __construct()
    {
        $this->type = self::TYPE_TAG;
    }
}
