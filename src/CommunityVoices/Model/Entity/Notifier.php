<?php

namespace CommunityVoices\Model\Entity;

/**
 * Handles entry notification
 */

use OutOfBoundsException;
use Exception;

use CommunityVoices\Model\Contract\StateObserver;

class Notifier implements StateObserver
{
    private $collector = [];

    private $notifier;

    /**
     * Sets the entry notifier to index new entries under
     * @param strinrg $notifier Notifier label
     */
    public function setNotifier($notifier)
    {
        $this->notifier = (string) $notifier;
    }

    /**
     * Adds an entry to the collector
     * @param string $key Reference key
     * @param string $message Entry message
     */
    public function addEntry($key, $message)
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

        if (!array_key_exists($key, $this->collector[$this->notifier])) {
            $this->collector[$this->notifier][$key] = [];
        }

        array_push($this->collector[$this->notifier][$key], $message);
    }

    /**
     * Detects entries
     * @return bool Boolean indicating if there are entries
     */
    public function hasEntries(): bool
    {
        return count($this->collector) > 0;
    }

    /**
     * Detects specific entry
     * @param  string  $key Entry key to search for
     * @param  string  $message Entry message to search for
     * @return boolean Boolean indicating if there are entries
     */
    public function hasEntry($key, $message = null): bool
    {
        if (is_null($this->notifier)) {
            throw new Exception('Notification notifier specified');
        }

        if (is_null($key)) {
            throw new Exception('Notification key not specified.');
        }

        if (!array_key_exists($this->notifier, $this->collector)) {
            return false;
        }

        if (!$message) {
            return array_key_exists($key, $this->collector[$this->notifier]);
        }

        return array_key_exists($key, $this->collector[$this->notifier])
                && in_array($message, $this->collector[$this->notifier][$key]);
    }

    /**
     * Gets entries from specific notifier
     * @param  string $notifier Notifier label
     * @return Array Collection of entries
     */
    public function getEntriesByNotifier($notifier)
    {
        if (!array_key_exists($notifier, $this->collector)) {
            throw new OutOfBoundsException('No notifier "'.$notifier.'"');
        }

        return $this->collector[$notifier];
    }

    /**
     * Gets all entries
     * @return Array Collection of entries
     */
    public function getEntries()
    {
        return $this->collector;
    }
}
