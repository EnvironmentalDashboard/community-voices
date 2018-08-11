<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Collection;
use CommunityVoices\Model\Contract\HasId;

class LocationCollection extends Collection
{

    private $parentId;

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Location;
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

    public function toArray()
    {
        $toReturn = ['locCollection' => []];

        foreach ($this->collection as $item) {
            $toReturn['locCollection'][] = $item->toArray();
        }

        return $toReturn;
    }
}
