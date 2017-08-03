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
     * Populates given instance with values from array through setter methods
     *
     * @param object $instance The object to be populated
     * @param array $parameters A key-value array of the instance's parameters
     */
    public function populateEntity($instance, array $parameters)
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
     * @param  array $relations A key-value array of the relations to convert
     * @param  array $parameters Array of entity parameters
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
