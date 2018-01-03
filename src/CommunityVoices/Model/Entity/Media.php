<?php

/**
 * @ask validation of tags
 */

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Component\RelationalEntity;

class Media implements HasId
{
    const TYPE_SLIDE = 1;
    const TYPE_IMAGE = 2;
    const TYPE_QUOTE = 3;

    const STATUS_PENDING = 1;
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 3;

    protected $allowableType = [
        self::TYPE_SLIDE,
        self::TYPE_IMAGE,
        self::TYPE_QUOTE
    ];

    protected $allowableStatus = [
        self::STATUS_PENDING,
        self::STATUS_REJECTED,
        self::STATUS_APPROVED
    ];

    private $id;

    private $addedBy;
    private $dateCreated;

    protected $type;

    private $status;

    private $tagCollection;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $input = (int) $id;

        if ($input > 0) {
            $this->id = $input;
        }
    }

    public function getAddedBy()
    {
        return $this->addedBy;
    }

    public function setAddedBy($addedBy)
    {
        if ($addedBy instanceof User && $addedBy->getId()) {
            $this->addedBy = $addedBy;
        }
    }

    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (in_array($type, $this->allowableType)) {
            $this->type = (int) $type;
        }
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function setStatus($status)
    {
        if (in_array($status, $this->allowableStatus)) {
            $this->status = (int) $status;
        }
    }

    public function getTagCollection()
    {
        return $this->tagCollection;
    }

    public function setTagCollection(GroupCollection $tagCollection)
    {
        $this->tagCollection = $tagCollection;
    }

    public function toArray()
    {
        return ['media' => [
            'id' => $this->id,
            //'addedBy' => $this->addedBy->toArray(),
            'dateCreated' => $this->dateCreated,
            'type' => $this->type,
            'status' => $this->status,
            //'tagCollection' => $this->tagCollection->toArray()
        ]];
    }
}
