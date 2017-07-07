<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract;

class Session extends Mapper
{
    public function prepare()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function save(Contract\Cookieable $instance)
    {
        $_SESSION[$instance->getUniqueLabel()] = $instance->toJson();
    }

    public function fetch(Contract\Cookieable $instance)
    {
        $session = $_SESSION[$instance->getUniqueLabel()];

        if (!$session) {
            return false;
        }

        $this->applyValues($instance, $session);
    }

    public function delete(Contract\Cookieable $instance)
    {
        unset($_SESSION[$instance->getUniqueLabel()]);
    }
}
