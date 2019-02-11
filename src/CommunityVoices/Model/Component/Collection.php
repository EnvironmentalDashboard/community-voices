<?php

namespace CommunityVoices\Model\Component;

use \ArrayAccess;
use \Countable;
use \Iterator;

abstract class Collection implements ArrayAccess, Countable, Iterator
{
    protected $collection = [];

    private $position = 0;

    abstract protected function makeEntity();

    /**
     * Method for adding a new entity from skeleton parameters to the collection
     * @param array $parameters A key-value array of the instance's parameters
     */
    public function addEntityFromParams(array $parameters)
    {
        $instance = $this->makeEntity();

        $this->populateEntity($instance, $parameters);

        $this->addEntity($instance);

        return $instance;
    }

    /**
    * Populates given instance with values from array through setter methods
    *
    * @param Object $instance The instance to be populated
    * @param array $parameters A key-value array of the instance's parameters
     */
    private function populateEntity($instance, array $parameters)
    {
        foreach ($parameters as $key => $value) {
            $method = 'set' . str_replace('_', '', $key);
            if (method_exists($instance, $method)) {
                $instance->{$method}($value);
            }
        }
    }

    /**
     * Adds an entity to the collection
     * @param Object $instance The instance to add
     */
    public function addEntity($instance)
    {
        $this->collection[] = $instance;
    }

    
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Clears the collection, resets the index for re-initialization of collection.
     * @param Object $instance The instance to be cleared
     */
    public function clear()
    {
        $this->collection = [];
        $this->index = 0;
    }

    /**
    * ArrayAccess implemention
    */
    public function offsetExists($offset)
    {
        return isset($this->collection[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->collection[$offset])
            ? $this->collection[$offset]
            : null;
    }

    public function offsetSet($offset, $value)
    {
        $this->collection[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->collection[$offset]);
    }

    /**
    * Countable implementation
    */
    public function count() :int
    {
        return count($this->collection);
    }

    /**
    * Iterator implementation
    */
    public function current()
    {
        return $this->collection[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function valid()
    {
        return isset($this->collection[$this->position]);
    }
}
