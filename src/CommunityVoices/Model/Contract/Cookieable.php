<?php

namespace CommunityVoices\Model\Contract;

interface Cookieable
{
    public function getUniqueLabel();

    public function getExpiresOn();

    public function toJSON();
}
