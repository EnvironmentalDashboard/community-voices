<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Component\Mapper;
use ReflectionClass;

class Cache extends Mapper
{
    private $cache = [];

    public function save(HasId $instance)
    {
        $this->cache[$this->generateSignature($instance)] = $instance;
    }

    public function fetch(HasId $instance)
    {
        $signature = $this->generateSignature($instance);

        if (!isset($this->cache[$signature])) {
            return false;
        }

        $cachedInstance = $this->cache[$signature];

        $reflection = new ReflectionClass($cachedInstance);

        foreach ($reflection->getProperties() as $property) {
            $property->setAccessible(true);
            $property->setValue($instance, $property->getValue($cachedInstance));
        }
    }

    public function delete(HasId $instance)
    {
        unset($this->cache[$this->generateSignature($instance)]);
    }

    public function exists(HasId $instance)
    {
        return array_key_exists($this->generateSignature($instance), $this->cache);
    }

    private function generateSignature(HasId $instance)
    {
        $reflection = new ReflectionClass($instance);

        return implode([$reflection->getName(), $instance->getId()], "\\");
    }
}
