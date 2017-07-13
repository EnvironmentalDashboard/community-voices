<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contracts\HasId;
use CommunityVoices\Model\Component\RelationalEntity;
use CommunityVoices\Model\Entities;

class Media extends RelationalEntity implements HasId
{
    const TYPE_SLIDE = 1;
    const TYPE_IMAGE = 2;
    const TYPE_QUOTE = 3;

    const STATUS_PENDING = 1;
    const STATUS_REJECTED = 2;
    const STATUS_APPROVED = 3;

    private $relations = [
        'addedBy' => Entities\User::class
    ];

    private $id;

    private $addedBy;
    private $dateCreated;

    private $type;

    private $approvedStatus;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getAddedBy()
    {
        return $this->addedBy;
    }

    public function setAddedBy($addedBy)
    {
        $this->addedBy = $addedBy;
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
        $this->type = $type;
    }

    public function getApprovedStatus()
    {
        return $this->approvedStatus;
    }

    public function setApprovedStatus($approvedStatus)
    {
        $this->approvedStatus = $approvedStatus;
    }
}
