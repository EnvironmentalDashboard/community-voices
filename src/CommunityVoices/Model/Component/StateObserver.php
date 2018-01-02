<?php

namespace CommunityVoices\Model\Component;

/**
 * Handles state observing
 *
 * @todo getEntry
 */

use OutOfBoundsException;
use Exception;

use CommunityVoices\Model\Contract\FlexibleObserver;

class StateObserver implements FlexibleObserver
{
    private $collector = [];

    private $subject;

    /**
     * Sets the entry subject to index new entries under
     * @param string $subject subject label
     */
    public function setSubject($subject)
    {
        $this->subject = (string) $subject;
    }

    /**
     * Adds an entry to the collector
     * @param string $key
     * @param string $value
     */
    public function addEntry($key, $value)
    {
        if (is_null($this->subject)) {
            throw new Exception('Notification subject specified');
        }

        if (is_null($key)) {
            throw new Exception('Notification key not specified.');
        }

        if (!array_key_exists($this->subject, $this->collector)) {
            $this->collector[$this->subject] = [];
        }

        if (!array_key_exists($key, $this->collector[$this->subject])) {
            $this->collector[$this->subject][$key] = [];
        }

        array_push($this->collector[$this->subject][$key], $value);
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
        if (is_null($this->subject)) {
            throw new Exception('Notification subject specified');
        }

        if (is_null($key)) {
            throw new Exception('Notification key not specified.');
        }

        if (!array_key_exists($this->subject, $this->collector)) {
            return false;
        }

        if (!$message) {
            return array_key_exists($key, $this->collector[$this->subject]);
        }

        return array_key_exists($key, $this->collector[$this->subject])
                && in_array($message, $this->collector[$this->subject][$key]);
    }

    /**
     * Gets entries from specific subject
     * @param  string $subject Subject label
     * @return Array Collection of entries
     */
    public function getEntriesBySubject($subject)
    {
        if (!array_key_exists($subject, $this->collector)) {
            throw new OutOfBoundsException('No subject "'.$subject.'"');
        }

        return $this->collector[$subject];
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
