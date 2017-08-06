<?php

namespace Mock;

use CommunityVoices\Model\Component\Collection;

class EntityCollection extends Collection
{
    protected function makeEntity()
    {
        return new Entity;
    }
}
