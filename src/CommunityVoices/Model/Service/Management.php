<?php

namespace CommunityVoices\Model\Service;

/**
 * Consider making two subdirectories inside Service:
 * Management and Lookup
 * Then can have QuoteManagement simply named Quote.
 * This would be in the Service/ directory.
 *
 * More of a need of common functions would be needed
 * before this is truly useful.
 */
class Management
{
    // Sets relevant attributes in an entity.
    protected function setEntityAttributes($model, $attributes, $instructions)
    {
        foreach ($instructions as $variable) {
            if (key_exists($variable, $attributes)) {
                $method = 'set' . ucfirst($variable);

                $model->{$method}($attributes[$variable]);
            }
        }
    }
}
