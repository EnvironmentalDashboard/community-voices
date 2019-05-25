<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class ContentCategory extends Group
{
    private $groupId;
    private $image;
    private $color;

    // Not currently in database.
    protected $probability; /* @TODO required, number >= 0 */

    public function __construct()
    {
        $this->type = self::TYPE_CONT_CAT;
    }

    // This should be locked into an int once the database has no slides
    // that lack a content category.
    public function setGroupId($id)
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

    public function getColor()
    {
        return $this->color;
    }

    public function setColor($color)
    {
        $this->color = $color;
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

    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        // This checks for label being present.
        return parent::validateForUpload($stateObserver);
    }

    public function toArray()
    {
        return ['contentCategory' => array_merge(parent::toArray()['group'], [
            'image' => $this->image ? $this->image->toArray() : null,
            'color' => $this->color,
            'probability' => $this->probability
        ])];
    }
}
