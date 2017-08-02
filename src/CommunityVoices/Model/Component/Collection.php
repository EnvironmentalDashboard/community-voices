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
     * ArrayAccess implemention
     */
     public function offsetExists($offset)
     {
         return isset($this->collection[$offset]);
     }

    public function offsetGet($offset)
    {
        return issest($this->collection[$offset])
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
