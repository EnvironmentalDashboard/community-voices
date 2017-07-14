<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Mapper;
use PHPUnit\Framework\TestCase;
use PDO;
use RuntimeException;

class MapperFactoryTest extends TestCase
{
    public function test_Data_Mapper_Creation()
    {
        $pdo = $this->createMock(PDO::class);

        $mapperFactory = new MapperFactory($pdo, [], []);
        $mapper = $mapperFactory->createDataMapper(Mapper\User::class);

        $this->assertTrue($mapper instanceof Mapper\User);

        $mapper2 = $mapperFactory->createDataMapper(Mapper\User::class);

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Illegal_Mapper_Creation()
    {
        $pdo = $this->createMock(PDO::class);

        $this->expectException(RuntimeException::class);

        $mapperFactory = new MapperFactory($pdo, [], []);
        $mapperFactory->createDataMapper(Mapper\ClassWhichDoesntExist::class);
    }

    public function test_Session_Mapper_Creation()
    {
        @session_start();

        $pdo = $this->createMock(PDO::class);

        $mapperFactory = new MapperFactory($pdo, [], []);
        $mapper = $mapperFactory->createSessionMapper(Mapper\Session::class);

        $this->assertTrue($mapper instanceof Mapper\Session);

        $mapper2 = $mapperFactory->createSessionMapper(Mapper\Session::class);

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Cookie_Mapper_Creation()
    {
        $pdo = $this->createMock(PDO::class);

        $mapperFactory = new MapperFactory($pdo, [], []);
        $mapper = $mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $this->assertTrue($mapper instanceof Mapper\Cookie);

        $mapper2 = $mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }
}
