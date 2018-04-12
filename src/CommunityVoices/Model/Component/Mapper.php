<?php

namespace CommunityVoices\Model\Component;

/**
* Mapper component to be extended by entity mappers.
*/

class Mapper
{
    /**
    * Populates given instance with values from array through setter methods
    *
    * @param object $instance The object to be populated
    * @param array $parameters A key-value array of the instance's parameters
    */
    public function populateEntity($instance, array $parameters, $setterPrefix = 'set')
    {
        foreach ($parameters as $key => $value) {
            $method = $setterPrefix . str_replace('_', '', $key);

            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }
    }

    public function convertRelations(array $relations, array $entry)
    {
        $entities = [];
        $collections = [];

        if (array_key_exists('Entity', $relations)) {
            $entities = $this->makeEntityRelations($relations['Entity'], $entry);
        }

        if (array_key_exists('Collection', $relations)) {
            $collections = $this->makeCollectionRelations($relations['Collection'], $entry);
        }

        return array_merge($entities, $collections);
    }

    private function makeEntityRelations(array $relations, array $entry)
    {
        return $this->makeRelations($relations, $entry, 'set');
    }

    private function makeCollectionRelations(array $relations, array $entry)
    {
        return $this->makeRelations($relations, $entry, 'for');
    }

    private function makeRelations(array $relations, array $entry, $setterPrefix)
    {
        /**
        * Holds all objects to be returned
        * @var array
        */
        $objects = [];

        /**
        * Loop through all specified relations
        */
        foreach ($relations as $attribute => $implementation) {
            $static = [];
            $attributes = $implementation['attributes'];
            $class = $implementation['class'];

            if (array_key_exists('static', $implementation)) {
                $static = $implementation['static'];
            }

            /**
            * Holds parameters that will be populated onto the new instance
            * @var array
            */
            $params = array_merge(
                $this->mapEntryToEntityParams($entry, $attributes),
                $static
            );

            /**
            * Create new instance and populate
            * @var Instance of $class
            */
            $entity = new $class;
            $this->populateEntity($entity, $params, $setterPrefix);

            $objects[$attribute] = $entity;
        }

        return $objects;
    }

    private function mapEntryToEntityParams($entry, $attributes)
    {
        $params = [];

        foreach ($attributes as $key => $queryKey) {
            /**
            * If the mapped key doesn't exist in the provided entry, skip
            * this attribute
            */
            if (!array_key_exists($queryKey, $entry) || !$entry[$queryKey]) {
                continue;
            }

            $params[$key] = $entry[$queryKey];
        }

        return $params;
    }
}
