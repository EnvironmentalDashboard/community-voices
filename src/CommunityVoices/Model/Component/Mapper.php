<?php

namespace CommunityVoices\Model\Component;

/**
 * Mapper component to be extended by entity mappers.
 *
 * Code taken from teresko/palladium
 * @link https://github.com/teresko/palladium/blob/master/src/Palladium/Component/DataMapper.php
 */

class Mapper
{
    /**
     * Method for populating the given instance with values from the array via setters
     *
     * @param object $instance The object to be populated with values
     * @param array $parameters A key-value array, that will be matched to setters
     */
    public function applyValues($instance, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }
    }

    /**
     * Runs over $parameters and replaces any ID relations indicated by keys in
     * $relations with new instance entities;
     *
     * @param  array  $relations A key-value array of the relations to convert
     * @param  array  $parameters Array of entity parameters
     * @return array New parameter array
     */
    public function convertRelationsToEntities(array $relations, array $parameters)
    {
        foreach ($relations as $key => $value) {
            $key = strtolower(preg_replace('/([^A-Z])([A-Z])/', "$1_$2", $key));

            if (!array_key_exists($key, $parameters)) {
                continue;
            }

            $instance = new $value;
            $instance->setId($parameters[$key]);

            $parameters[$key] = $instance;
        }

        return $parameters;
    }
}
