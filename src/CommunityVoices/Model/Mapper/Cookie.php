<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract;

class Cookie extends Mapper
{
    private $request;
    private $response;

    /**
     * @codeCoverageIgnore
     */
    public function __construct($request, $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function save(Contract\Cookieable $instance)
    {
        $this->response->addCookie(
            $instance->getUniqueLabel(),
            $instance->toJson(),
            ['expires' => $instance->getExpiresOn()]
        );
    }

    public function fetch(Contract\Cookieable $instance)
    {
        $cookie = $this->request->getCookie($instance->getUniqueLabel());

        if (!$cookie) {
            return false;
        }

        $this->applyValues($instance, json_decode($cookie, true));
    }

    public function delete(Contract\Cookieable $instance)
    {
        $this->response->removeCookie($instance->getUniqueLabel());
    }
}
