<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Collection;

class GroupCollection extends Collection
{
    protected function makeEntity()
    {
        return new Group;
    }
}
