<?php

namespace CommunityVoices\Model\Contract;

interface FlexibleObserver
{
    public function setSubject($subject);

    public function addEntry($key, $value);

    public function hasEntries(): bool;

    public function getEntriesBySubject($subject);

    public function getEntries();
}
