<?php

namespace CommunityVoices\Model\Component;

use RuntimeException;
use PDO;

class MapperFactory
{
    private $dbHandler;

    private $request;

    private $cache = [];

    public function __construct(PDO $dbHandler, $request = null)
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
