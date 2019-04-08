<?php

namespace CommunityVoices\Model\Contract;

interface FlexibleObserver
{
    public function setSubject($subject);

    public function addEntry($key, $value);

    public function hasEntries(): bool;
    public function hasSubjectEntries($subject): bool;

    public function hasEntry($key, $value);

    public function getEntry($key);

    public function getEntriesBySubject($subject);

    public function getEntries();
}
