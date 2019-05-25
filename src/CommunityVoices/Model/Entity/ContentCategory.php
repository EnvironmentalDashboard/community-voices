<?php

namespace CommunityVoices\Model\Entity;

class ContentCategory extends Group
{
    private $groupId;
    private $image;

    // Not currently in database.
    protected $probability; /* @TODO required, number >= 0 */

    public function __construct()
    {
        $this->type = self::TYPE_CONT_CAT;
    }

    public function setGroupId(int $id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    // Not currently in database
    public function setProbability($probability)
    {
        $this->probability = $probability;
    }

    public function getProbability()
    {
        return $this->probability;
    }

    public function toArray()
    {
        return ['contentCategory' => array_merge(parent::toArray()['group'], [
            'image' => $this->image->toArray(),
            'probability' => $this->probability
        ])];
    }
}
