<?php

namespace CommunityVoices\Model\Component;

use OutOfBoundsException;
use Exception;

use CommunityVoices\Model\Contract\ErrorNotifier;

class Notifier implements ErrorNotifier
{
    private $errors = [];

    private $notifier;

    public function setNotifier($notifier)
    {
        $this->notifier = (string) $notifier;
    }

    public function addError($key, $message)
    {
        if(is_null($this->notifier)) {
            throw new Exception('Notification notifier specified');
        }

        if(is_null($key)) {
            throw new Exception('Notification key not specified.');
        }

        if(!array_key_exists($this->notifier, $this->errors)) {
            $this->errors[$this->notifier] = [];
        }

        $this->errors[$this->notifier][$key] = $message;
    }

    public function hasErrors(): bool
    {
        return count($this->errors) > 0;
    }

    public function getErrorsByNotifier($notifier)
    {
        if(!array_key_exists($notifier, $this->errors)) {
            throw new OutOfBoundsException('No notifier "'.$notifier.'"');
        }

        return $this->errors[$notifier];
    }

    public function getErrors()
    {
        if(count($this->errors) <= 0) {
            return ;
        }

        return $this->errors;
    }
}
