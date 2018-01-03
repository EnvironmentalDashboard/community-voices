<?php

namespace CommunityVoices\App\Website\Component;

use RuntimeException;
use PDO;
use ReflectionClass;
use CommunityVoices\Model\Mapper\Cache;

class MapperFactory
{
    private $request;
    private $response;

    private $cache = [];

    public function __construct($request, $response = null)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function createCacheMapper()
    {
        return $this->create(Cache::class, null);
    }

    public function createCookieMapper($class)
    {
        return $this->create($class, [$this->request, $this->response]);
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
