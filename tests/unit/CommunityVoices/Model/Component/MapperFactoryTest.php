<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Mapper;
use PHPUnit\Framework\TestCase;
use PDO;
use RuntimeException;

/**
 * @covers CommunityVoices\Model\Component\MapperFactory
 */
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

    public function test_Cache_Mapper_Creation()
    {
        $pdo = $this->createMock(PDO::class);

        $mapperFactory = new MapperFactory($pdo, [], []);

        $mapper = $mapperFactory->createCacheMapper();

        $this->assertTrue($mapper instanceof Mapper\Cache);

        $mapper2 = $mapperFactory->createCacheMapper();

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }

    public function test_Client_State_Mapper_Creation()
    {
        $pdo = $this->createMock(PDO::class);

        $mapperFactory = new MapperFactory($pdo, [], []);

        $mapper = $mapperFactory->createClientStateMapper();

        $this->assertTrue($mapper instanceof Mapper\ClientState);

        $mapper2 = $mapperFactory->createClientStateMapper();

        $this->assertSame($mapper, $mapper2); //confirm proper caching
    }
}
