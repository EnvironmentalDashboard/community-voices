<?php

/**
 * @ask validation of tags
 */

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\HasId;

class Media implements HasId
{
    const TYPE_SLIDE = 1;
    const TYPE_IMAGE = 2;
    const TYPE_QUOTE = 3;
    const TYPE_ARTICLE = 4;

    const STATUS_PENDING = 1;
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 3;

    protected $allowableType = [
        self::TYPE_SLIDE,
        self::TYPE_IMAGE,
        self::TYPE_QUOTE,
        self::TYPE_ARTICLE
    ];

    protected $allowableStatus = [
        self::STATUS_PENDING => "pending",
        self::STATUS_REJECTED => "rejected",
        self::STATUS_APPROVED => "approved"
    ];

    private $id;

    private $addedBy;
    private $dateCreated;

    protected $type;

    private $status;

    private $tagCollection;
    private $contentCategoryCollection;

    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId($id)
    {
        if (is_int($id) || is_null($id)) {
            $this->id = $id;
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
        $this->dateCreated = strtotime($dateCreated);
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

    // truthy is approved,
    // falsy is pending,
    // rejected requires work to set
    // (this probably all needs to be revised)
    public function setStatus($status)
    {
        if (array_key_exists($status, $this->allowableStatus)) {
            $this->status = (int) $status;
        } else if ($status) {
            $this->status = self::STATUS_APPROVED;
        } else {
            $this->status = self::STATUS_PENDING;
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

    public function getContentCategoryCollection()
    {
        return $this->contentCategoryCollection;
    }

    public function setContentCategoryCollection(GroupCollection $contentCategoryCollection)
    {
        $this->contentCategoryCollection = $contentCategoryCollection;
    }

    public function toArray()
    {
        return ['media' => [
            'id' => $this->id,
            'addedBy' => $this->addedBy ? $this->addedBy->toArray() : null,
            'dateCreated' => date("Y-m-d H:i:s", $this->dateCreated),
            'type' => $this->type,
            'status' => $this->allowableStatus[$this->status ?: self::STATUS_REJECTED],
            'tagCollection' => $this->tagCollection ? $this->tagCollection->toArray() : null,
            'contentCategoryCollection' => $this->contentCategoryCollection ?
                $this->contentCategoryCollection->toArray() : null
        ]];
    }
}
