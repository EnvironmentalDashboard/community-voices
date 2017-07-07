<?php

namespace CommunityVoices\Model\Component;

use RuntimeException;
use PDO;

class MapperFactory
{
    private $dbHandler;

    private $request;

    private $cache = [];

    public function __construct(PDO $dbHandler, $request)
    {
        $this->dbHandler = $dbHandler;
        $this->request = $request;
    }

    public function createDataMapper($class)
    {
        return $this->create($class, $this->dbHandler);
    }

    public function createCookieMapper($class)
    {
        return $this->create($class, $this->request);
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

        $this->cache[$class] = new $class($handler);

        if ($prepare) {
            $prepare($this->cache[$class]);
        }

        return $this->cache[$class];
    }
}
