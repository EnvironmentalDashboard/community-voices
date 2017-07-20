<?php

namespace CommunityVoices\Model\Mapper;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Cache
 */
class CacheTest extends TestCase
{
    public function test_Fetching_Non_Existent_Instance()
    {
        $instance = $this->createMock(Entity\User::class);

        $mapper = new Cache;

        $this->assertFalse($mapper->fetch($instance));
    }

    public function test_Saving_Retrieving_Instance()
    {
        $instance = new Entity\User;
        $instance->setId(5);
        $instance->setFirstName('Foo');

        $mapper = new Cache;
        $mapper->save($instance);

        $otherInstance = new Entity\User;
        $otherInstance->setId(5);

        $mapper->fetch($otherInstance);

        $this->assertSame($instance->getFirstName(), $otherInstance->getFirstName());
    }

    public function test_Deleting_Cached_Instance()
    {
        $instance = new Entity\User;
        $instance->setId(5);
        $instance->setFirstName('Foo');

        $mapper = new Cache;
        $mapper->save($instance);

        $mapper->delete($instance);

        $this->assertFalse($mapper->fetch($instance));
    }

    public function test_Search_Existing_Instance()
    {
        $instance = new Entity\User;
        $instance->setId(5);
        $instance->setFirstName('Foo');

        $mapper = new Cache;
        $mapper->save($instance);


        $otherInstance1 = new Entity\User;
        $otherInstance1->setId(5);

        $otherInstance2 = new Entity\User;
        $otherInstance2->setId(4);


        $this->assertTrue($mapper->exists($otherInstance1));
        $this->assertFalse($mapper->exists($otherInstance2));
    }
}
