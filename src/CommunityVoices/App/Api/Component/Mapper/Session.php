<?php

namespace CommunityVoices\App\Api\Component\Mapper;

use CommunityVoices\Model\Contract\Sessionable;
use CommunityVoices\Model\Component\Mapper;

/**
 * @codeCoverageIgnore
 */
class Session extends Mapper
{
    public function prepare()
    {
        if (session_status() == PHP_SESSION_NONE) {
            //session_start();
        }
    }

    public function save(Sessionable $instance)
    {
        $_SESSION[$instance->getUniqueLabel()] = $instance->toJson();
    }

    public function fetch(Sessionable $instance)
    {
        if (!isset($_SESSION[$instance->getUniqueLabel()])) {
            return false;
        }

        $session = $_SESSION[$instance->getUniqueLabel()];

        $this->populateEntity($instance, json_decode($session, true));
    }

    public function delete(Sessionable $instance)
    {
        unset($_SESSION[$instance->getUniqueLabel()]);
    }
}
