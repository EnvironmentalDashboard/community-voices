<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Contract;
use ReflectionClass;

class Cache extends Mapper
{
    private $cache = [];

    public function save(Contract\HasId $instance)
    {
        $this->cache[$this->generateSignature($instance)] = $instance;
    }

    public function fetch(Contract\HasId $instance)
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

    public function delete(Contract\HasId $instance)
    {
        unset($this->cache[$this->generateSignature($instance)]);
    }

    public function exists(Contract\HasId $instance)
    {
        return array_key_exists($this->generateSignature($instance), $this->cache);
    }

    private function generateSignature(Contract\HasId $instance)
    {
        $reflection = new ReflectionClass($instance);

        return implode([$reflection->getName(), $instance->getId()], "\\");
    }
}
