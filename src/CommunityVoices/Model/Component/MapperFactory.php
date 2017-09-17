<?php

namespace CommunityVoices\Model\Component;

use RuntimeException;
use PDO;
use ReflectionClass;
use CommunityVoices\Model\Mapper;

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
        return $this->create(Mapper\Cache::class, null);
    }

    public function createSessionMapper($class)
    {
        $prepare = function ($instance) {
            $instance->prepare();
        };

        return $this->create($class, null, $prepare);
    }

    private function create($class, $handler, callable $prepare = null)
    {
        if (array_key_exists($class, $this->cache)) {
            return $this->cache[$class];
        }

        if (!class_exists($class)) {
            throw new RuntimeException("Mapper '{$class}' doesn't exist.");
        }

        if (is_array($handler) === false) {
            $this->cache[$class] = new $class($handler);
        } else {
            $reflection = new ReflectionClass($class);

            $this->cache[$class] = $reflection->newInstanceArgs($handler);
        }

        if ($prepare) {
            $prepare($this->cache[$class]);
        }

        return $this->cache[$class];
    }
}
