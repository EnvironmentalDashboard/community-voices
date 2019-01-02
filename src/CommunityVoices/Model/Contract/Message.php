<?php

namespace CommunityVoices\Model\Contract;

interface Message
{
    public function setTo($to);
    public function getTo();

    public function setFrom($from);
    public function getFrom();

    public function setSubject($subject);
    public function getSubject();

    public function setBody($body);
    public function getBody();
}
