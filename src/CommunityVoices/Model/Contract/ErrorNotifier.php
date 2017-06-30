<?php

namespace CommunityVoices\Model\Contract;

interface ErrorNotifier
{
    public function setNotifier($notifier);

    public function addError($key, $message);

    public function hasErrors(): bool;

    public function getErrorsByNotifier($notifier);

    public function getErrors();
}
