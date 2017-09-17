<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Contract\StateObserver;

/**
 * @codeCoverageIgnore
 *
 * @TODO Application state will change
 */
class ApplicationState
{
    public function prepare()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function save(StateObserver $state)
    {
        $_SESSION['state'] = $state->getEntries();
    }
}
