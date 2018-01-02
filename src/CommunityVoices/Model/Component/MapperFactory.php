<?php

namespace CommunityVoices\Model\Component;

use RuntimeException;
use PDO;
use ReflectionClass;
use CommunityVoices\Model;

class MapperFactory
{
    private $dbHandler;

    private $cache = [];

    public function __construct(PDO $dbHandler)
    {
        $this->dbHandler = $dbHandler;
    }

    public function createDataMapper($class)
    {
        return $this->create($class, $this->dbHandler);
    }

    public function createCacheMapper()
    {
        return $this->create(Model\Mapper\Cache::class, null);
    }

    public function createClientStateMapper()
    {
        return $this->create(Model\Mapper\ClientState::class, null);
    }

    private function create($class, $handler)
    {
        if (array_key_exists($class, $this->cache)) {
            return $this->cache[$class];
        }

        if (!class_exists($class)) {
            throw new RuntimeException("Mapper '{$class}' doesn't exist.");
        }

        $this->cache[$class] = new $class($handler);

        return $this->cache[$class];
    }
}
