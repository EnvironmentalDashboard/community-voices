<?php

namespace CommunityVoices\Model\Component;

use PHPUnit\Framework\TestCase;
use Mock\Entity;

class CollectionTest extends TestCase
{
    public function test_Adding_Entity_From_Parameters()
    {
        $parameters = [
            'id' => 3,
            'foo' => 'lipsum',
            'bar' => 'dolor'
        ];

        $expected = new Entity;
        $expected->setId(3);
        $expected->setFoo('lipsum');
        $expected->setBar('dolor');

        $collection = $this->getMockForAbstractClass(Collection::class);

        $collection
            ->method('makeEntity')
            ->will($this->returnCallback(function () {
                return new Entity;
            }));

        $collection->addEntityFromParams($parameters);

        $this->assertTrue(isset($collection[0]));
        $this->assertSame($collection[0]->getFoo(), $expected->getFoo());
        $this->assertEquals($collection[0], $expected);
    }

    public function test_ArrayAccess_Get()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $this->assertNull($collection[0]);

        $collection->addEntity($entity);

        $this->assertNotNull($collection[0]);
        $this->assertSame($collection[0], $entity);
    }

    public function test_ArrayAccess_Exists()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $this->assertFalse(isset($collection[0]));

        $collection->addEntity($entity);

        $this->assertTrue(isset($collection[0]));
    }

    public function test_ArrayAccess_Set()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $collection[0] = $entity;

        $this->assertTrue(isset($collection[0]));
    }

    public function test_ArrayAccess_Unset()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $collection[0] = $entity;

        $this->assertTrue(isset($collection[0]));

        unset($collection[0]);

        $this->assertFalse(isset($collection[0]));
    }

    public function test_Countable_Count()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $collection->addEntity($entity);

        $this->assertSame(count($collection), 1);

        $collection->addEntity($entity);

        $this->assertSame(count($collection), 2);

        unset($collection[0]);

        $this->assertSame(count($collection), 1);
    }

    public function test_Foreach_On_Collection()
    {
        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $item = new Entity;
        $collection->addEntity($item);

        $count = 0;
        foreach ($collection as $key => $value) {
            $count++;

            $this->assertSame(0, $key);
            $this->assertSame($item, $value);
        }

        $this->assertSame($count, count($collection));
    }

    public function test_Clearing_Collection()
    {
        $entity = new Entity;

        $collection = $this->getMockForAbstractClass(Collection::class);
        $collection->method('makeEntity');

        $collection->addEntity($entity);
        $collection->addEntity($entity);

        $this->assertSame(count($collection), 2);

        $collection->clear();

        $this->assertSame(count($collection), 0);
    }
}
