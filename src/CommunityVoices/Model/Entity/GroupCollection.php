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
    protected $parentId;

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

    public function forParent(HasId $entity)
    {
        $allowType = false;

        foreach ($this->allowableParentType as $className => $type) {
            if ($entity instanceof $className) {
                $this->parentType = $type;
                $allowType = true;
            }
        }

        if (!$allowType) {
            throw new \InvalidArgumentException(self::ERR_PARENT_TYPE_MISMATCH);
        }

        $id = (int) $entity->getId();

        if ($id) {
            $this->parentId = $id;
        }
    }

    public function toArray()
    {
        return ['groupCollection' => [
            'collection' => $this->collection,
            'groupType' => $this->groupType,
            'parentType' => $this->parentType,
            'parentId' => $this->parentId,
        ]];
    }
}
