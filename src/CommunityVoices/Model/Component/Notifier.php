<?php

namespace CommunityVoices\Model\Component;

/**
 * Handles error notification
 */

use OutOfBoundsException;
use Exception;

use CommunityVoices\Model\Contract\ErrorNotifier;

class Notifier implements ErrorNotifier
{
    private $collector = [];

    private $notifier;

    /**
     * Sets the error notifier to index new errors under
     * @param strinrg $notifier Notifier label
     */
    public function setNotifier($notifier)
    {
        $this->notifier = (string) $notifier;
    }

    /**
     * Adds an error to the collector
     * @param string $key Reference key
     * @param string $message Error message
     */
    public function addError($key, $message)
    {
        if (is_null($this->notifier)) {
            throw new Exception('Notification notifier specified');
        }

        if (is_null($key)) {
            throw new Exception('Notification key not specified.');
        }

        if (!array_key_exists($this->notifier, $this->collector)) {
            $this->collector[$this->notifier] = [];
        }

        $this->collector[$this->notifier][$key] = $message;
    }

    /**
     * Detects errors
     * @return bool Boolean indicating if there are errors
     */
    public function hasErrors(): bool
    {
        return count($this->collector) > 0;
    }

    /**
     * Detects specific error
     * @param  string  $key Error key to search for
     * @param  string  $message Error message to search for
     * @return boolean Boolean indicating if there are errors
     */
    public function hasError($key, $message = null): bool
    {
        if (!$message) {
            return array_key_exists($key, $this->collector);
        }

        return array_key_exists($key, $this->collector) && $this->collector[$key] === $message;
    }

    /**
     * Gets errors from specific notifier
     * @param  string $notifier Notifier label
     * @return Array Collection of errors
     */
    public function getErrorsByNotifier($notifier)
    {
        if (!array_key_exists($notifier, $this->collector)) {
            throw new OutOfBoundsException('No notifier "'.$notifier.'"');
        }

        return $this->collector[$notifier];
    }

    /**
     * Gets all errors
     * @return Array Collection of errors
     */
    public function getErrors()
    {
        return $this->collector;
    }
}
