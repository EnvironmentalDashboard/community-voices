<?php

namespace Mock;

use CommunityVoices\Model\Component\Collection;

class EntityCollection extends Collection
{
    protected $parentId;
    protected $parentType;

    protected function makeEntity()
    {
        return new Entity;
    }

    public function forParentType($type)
    {
        $this->parentType = $type;
    }

    public function getParentType()
    {
        return $this->parentType;
    }

    public function forParentId($id)
    {
        $input = (int) $id;

        if ($input > 0) {
            $this->parentId = $input;
        }
    }

    public function getParentId()
    {
        return $this->parentId;
    }
}
