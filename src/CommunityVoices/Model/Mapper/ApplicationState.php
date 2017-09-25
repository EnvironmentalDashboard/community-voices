<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract\StateObserver;

class ApplicationState extends Mapper
{
    private $state = false;

    public function save(StateObserver $state)
    {
        $this->state = $state->getEntries();
    }

    public function retrieve()
    {
        return $this->state;
    }
}
