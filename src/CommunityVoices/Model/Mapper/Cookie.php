<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract;

class Cookie extends Mapper
{
    private $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function save(Contract\Cookieable $instance)
    {
        setcookie(
            $instance->getUniqueLabel(),
            $instance->toJson(),
            $instance->getExpiresOn()
        );
    }

    public function fetch(Contract\Cookieable $instance)
    {
        $cookie = $this->request->getCookie($instance->getUniqueLabel());

        if (!$cookie) {
            return false;
        }

        $this->applyValues($instance, $cookie);
    }

    public function delete(Contract\Cookieable $instance)
    {
        setcookie(
            $instance->getUniqueLabel(),
            "",
            time() - 3600 // back-date expiration to delete
        );
    }
}
