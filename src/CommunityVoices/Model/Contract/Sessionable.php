<?php

namespace CommunityVoices\Model\Contract;

interface Sessionable
{
    public function getUniqueLabel();

    public function toJSON();
}
