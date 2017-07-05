<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Mapper;

class MapperFactory
{
    private $dbHandle;

    private $cache = [];

    public function create($class)
    {
        if (array_key_exists($class, $this->cache)) {
            return $this->cache[$class];
        }

        if (!class_exists($class)) {
            throw new RuntimeException("Mapper '{$class}' doesn't exist.");
        }

        $this->cache[$class] = new $class($dbHandle);
        return $this->cache[$class];
    }
}
