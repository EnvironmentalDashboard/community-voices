<?php

namespace CommunityVoices\App\Website\Component;

use CommunityVoices\Model\Contract\HasId;
use \OutOfBoundsException;

class CachedItem implements HasId
{
    private $id;
    private $value;

    public function __construct($id)
    {
        $this->setId($id);
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}
