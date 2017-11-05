<?php

namespace CommunityVoices\App\Website\Component;

use CommunityVoices\App\Website\Component\Mapper;
use PHPUnit\Framework\TestCase;
use RuntimeException;

/**
 * @covers CommunityVoices\App\Website\Component\MapperFactory
 */
class MapperFactoryTest extends TestCase
{
    public function test_Cache_Mapper_Creation()
    {
        $mapperFactory = new MapperFactory(null, null);

        $mapper = $mapperFactory->createCacheMapper();

        $this->assertTrue($mapper instanceof Mapper\Cache);

        $mapper2 = $mapperFactory->createCacheMapper();

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Cookie_Mapper_Creation()
    {
        $mapperFactory = new MapperFactory(null, null);
        $mapper = $mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $this->assertTrue($mapper instanceof Mapper\Cookie);

        $mapper2 = $mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Session_Mapper_Creation()
    {
        @session_start();

        $mapperFactory = new MapperFactory(null, null);
        $mapper = $mapperFactory->createSessionMapper(Mapper\Session::class);

        $this->assertTrue($mapper instanceof Mapper\Session);

        $mapper2 = $mapperFactory->createSessionMapper(Mapper\Session::class);

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Illegal_Mapper_Creation()
    {
        $this->expectException(RuntimeException::class);

        $mapperFactory = new MapperFactory(null, null);
        $mapperFactory->createCookieMapper(Mapper\ClassWhichDoesntExist::class);
    }
}
