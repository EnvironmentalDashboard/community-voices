<?php

namespace CommunityVoices\Model\Contract;

interface Cookieable extends Sessionable
{
    public function getExpiresOn();
}
