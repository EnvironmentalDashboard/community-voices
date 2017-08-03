<?php

namespace Mock;

use CommunityVoices\Model\Contract\HasId;

class Entity implements HasId
{
    protected $id;

    protected $foo;

    protected $bar;

    public function setId($id)
    {
        $input = (int) $id;

        if ($input > 0) {
            $this->id = (int) $input;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function setFoo($value)
    {
        $this->foo = $value;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setBar($value)
    {
        $this->bar = $value;
    }

    public function getBar()
    {
        return $this->bar;
    }
}
