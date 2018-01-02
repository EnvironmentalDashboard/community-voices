<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract\FlexibleObserver;

class ClientState extends Mapper
{
    private $state = false;

    public function save(FlexibleObserver $observer)
    {
        $this->state = $observer;
    }

    public function retrieve()
    {
        return $this->state;
    }
}
