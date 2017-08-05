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
     * @link https://github.com/teresko/palladium/blob/master/src/Palladium/Component/DataMapper.php
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
     * Converts any values in $entry meeting keys specified in the $relations array
     * to domain objects. Attributes on the domain object are specified in the
     * 'attributes' array
     *
     * E.g., a relation map like so:
     *
     * [
     *     'addedBy' => [                       // key to assign new instance to
     *         'attributes' => [
     *             'class' => 'User'            // class to create instance of
     *             'id' => 'creator_id'         // maps instance attr. to query key
     *         ]
     *     ]
     * ]
     *
     * Will cause the method to look through the $entry array for a key of
     * `creator_id`. If found, create an instance of User and set the entity's
     * `id` attribute to $entry['creator_id']. The entity is returned in a
     * key-value array, accessed by the key `addedBy`. Multiple attributes may be
     * specified.
     *
     * @param  array  $relations   Relational configuration
     * @param  array  $entry       Array-key of entry attributes & values
     * @return array               An array of the newly created parameters w/ entities
     */
    public function makeSingleCardinalityRelations(array $relations, array $entry)
    {
        /**
         * Holds all entities to be returned
         * @var array
         */
        $entities = [];

        /**
         * Loop through all specified relations
         */
        foreach ($relations as $attribute => $implementation) {
            $attributes = $implementation['attributes'];
            $class = $implementation['class'];

            /**
             * Holds parameters that will be populated onto the new instance
             * @var array
             */
            $params = $this->mapEntryToEntityParams($entry, $attributes);

            /**
             * Create new instance and populate
             * @var Instance of $class
             */
            $entity = new $class;
            $this->populateEntity($entity, $params);

            $entities[$attribute] = $entity;
        }

        return $entities;
    }

    /**
     * Loops through an array of $entries and converts any values meeting keys
     * specified in the $relations array to domain object collections. Attributes
     * on the collection's items are specified in the 'attributes' array.
     *
     * E.g., a relation map like so:
     *
     * [
     *     'addedBy' => [                       // key to assign new instance to
     *         'attributes' => [
     *             'class' => 'UserCollection'  // class to create instance of
     *             'id' => 'creator_id'         // maps instance attr. to query key
     *         ]
     *     ]
     * ]
     *
     * Will cause the method to loop over all $entries and look through each entry
     * for `creator_id`. If found, built an *item* of UserCollection (i..e, User)
     * and set the `id` attribute to $entry['creator_id']. The collection is
     * returned in a key-value array, accessed by the key `addedBy`
     *
     * @param  array  $relations   Relation configuration
     * @param  array  $entry       [description]
     * @return array               An array of the newly created parameters w/ collections
     */
    public function makeMultipleCardinalityRelations(array $relations, array $entries)
    {
        /**
         * Holds all collections to be returned
         * @var array
         */
        $collections = [];

        /**
         * Loop every entry
         */
        foreach ($entries as $entry) {

            /**
             * Loop through all specified relations
             */
            foreach ($relations as $attribute => $implementation) {
                $attributes = $implementation['attributes'];
                $class = $implementation['class'];

                /**
                 * Create the collection if it doesn't exist
                 */
                if (!array_key_exists($attribute, $collections)) {
                    $collections[$attribute] = new $class;
                }

                /**
                 * Holds parameters that will be populated onto the new instance
                 * @var array
                 */
                $params = $this->mapEntryToEntityParams($entry, $attributes);

                /**
                 * Create new collection item from params
                 */
                if (!$params) {
                    continue;
                }

                $collections[$attribute]->addEntityFromParams($params);
            }
        }

        return $collections;
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
