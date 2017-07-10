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

    public function save(Contract\Sessionable $instance)
    {
        $_SESSION[$instance->getUniqueLabel()] = $instance->toJson();
    }

    public function fetch(Contract\Sessionable $instance)
    {
        if (!isset($_SESSION[$instance->getUniqueLabel()])) {
            return false;
        }

        $session = $_SESSION[$instance->getUniqueLabel()];

        $this->applyValues($instance, json_decode($session, true));
    }

    public function delete(Contract\Sessionable $instance)
    {
        unset($_SESSION[$instance->getUniqueLabel()]);
    }
}
