<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Collection;
use CommunityVoices\Model\Contract\HasId;

class GroupCollection extends Collection
{
    const ERR_PARENT_TYPE_MISMATCH = 'Parent must be instance of Media or Location.';

    const PARENT_TYPE_MEDIA = 0;
    const PARENT_TYPE_LOCATION = 1;

    const GROUP_TYPE_TAG = 1;
    const GROUP_TYPE_ORG_CAT = 2;
    const GROUP_TYPE_CONT_CAT = 3;

    protected $allowableParentType = [
        Media::class => self::PARENT_TYPE_MEDIA,
        Location::class => self::PARENT_TYPE_LOCATION
    ];

    protected $allowableGroupType = [
        self::GROUP_TYPE_TAG,
        self::GROUP_TYPE_ORG_CAT,
        self::GROUP_TYPE_CONT_CAT
    ];

    protected $groupType;
    protected $parentType;
    protected $parent;

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Group;
    }

    public function forGroupType($type)
    {
        if (in_array($type, $this->allowableGroupType, true)) {
            $this->groupType = $type;
        }
    }

    public function getGroupType()
    {
        return $this->groupType;
    }

    public function forParentType($type)
    {
        if (in_array($type, $this->allowableParentType, true)) {
            $this->parentType = $type;
        }
    }

    public function getParentType()
    {
        return $this->parentType;
    }

    public function getParentId()
    {
        if (!$this->parent || !$this->parent->getId()) {
            return ;
        }

        return $this->parent->getId();
    }

    public function forParent(HasId $parent)
    {
        $allowType = false;

        foreach ($this->allowableParentType as $className => $type) {
            if ($parent instanceof $className) {
                $this->parentType = $type;
                $allowType = true;
            }
        }

        if (!$allowType) {
            throw new \InvalidArgumentException(self::ERR_PARENT_TYPE_MISMATCH);
        }

        $this->parent = $parent;
    }

    public function toArray()
    {
        $toReturn = ['groupCollection' => []];

        foreach ($this->collection as $item) {
            $toReturn['groupCollection'][] = $item->toArray();
        }

        return $toReturn;
    }

    /**
     * Propagates the collection from an array of group Ids
     *
     * @param  array $id Array of Ids from which entities will be generated
     */
    public function propagateWithEntitiesFromIds($ids)
    {
        foreach ($ids as $id) {
            $group = $this->makeEntity();

            $group->setId($id);

            $this->addEntity($group);
        }
    }
}
