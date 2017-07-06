<?php

namespace CommunityVoices\Model\Contract;

interface StatusObserver
{
    public function setNotifier($notifier);

    public function addEntry($key, $message);

    public function hasEntries(): bool;

    public function getEntriesByNotifier($notifier);

    public function getEntries();
}
