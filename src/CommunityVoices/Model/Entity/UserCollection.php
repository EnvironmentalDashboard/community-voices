<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Collection;

class UserCollection extends Collection
{
    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new User;
    }

    public function toArray()
    {
        $toReturn = ['userCollection' => []];

        foreach ($this->collection as $item) {
            $toReturn['userCollection'][] = $item->toArray();
        }

        return $toReturn;
    }
}
