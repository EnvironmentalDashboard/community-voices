<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Contract\StateObserver;

class ApplicationState
{
    public function prepare()
    {
        session_start();
    }

    public function save(StateObserver $state)
    {
        $_SESSION['state'] = $state->getEntries();
    }
}
