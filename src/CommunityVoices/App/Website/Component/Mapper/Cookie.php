<?php

namespace CommunityVoices\App\Website\Component\Mapper;

use CommunityVoices\Model\Contract;
use Symfony\Component\HttpFoundation\Cookie;

class Cookie extends \CommunityVoices\Model\Component\Mapper
{
    private $request;
    private $response;

    private $setCookies = [];
    private $clearCookies = [];

    public function __construct($request, $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function provideResponseHandler($response)
    {
        $this->response = $response;
    }

    public function mapToResponse()
    {
        if (!$this->response) {
            throw new \Exception("No response handler.");
        }

        foreach ($this->setCookies as $key => $cookie) {
            $this->response->headers->setCookie($cookie);
        }

        foreach ($this->clearCookies as $key => $name) {
            $this->response->headers->clearCookie($name);
        }
    }

    public function fetch(Contract\Cookieable $instance)
    {
        $cookie = $this->request->cookies->get($instance->getUniqueLabel());

        if (!$cookie) {
            return false;
        }

        $this->populateEntity($instance, json_decode($cookie, true));
    }

    public function save(Contract\Cookieable $instance)
    {
        $cookie = new Cookie(
            $instance->getUniqueLabel(),
            $instance->toJson(),
            $instance->getExpiresOn()
        );

        $this->setCookies[] = $cookie;

        $this->request->cookies->set($instance->getUniqueLabel(), $instance->toJson());
    }

    public function delete(Contract\Cookieable $instance)
    {
        $this->clearCookies[] = $instance->getUniqueLabel();

        $this->request->cookies->remove($instance->getUniqueLabel());
    }
}
