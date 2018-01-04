<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\Model\Entity;

class ContainedItem
{
    private $instance;

    private $callback;

    public function __construct($instance, $callback)
    {
        $this->instance = $instance;
        $this->callback = $callback;
    }

    public function __call($method, $args)
    {
        return call_user_func($this->callback, $method, $args, $this->instance);
    }
}
