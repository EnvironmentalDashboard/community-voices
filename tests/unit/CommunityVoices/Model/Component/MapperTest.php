<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Entity\User;
use PHPUnit\Framework\TestCase;
use Mock\Entity;

/**
 * @covers CommunityVoices\Model\Component\Mapper
 */
class MapperTest extends TestCase
{
    public function test_Populating_Entity_Properties()
    {
        $entity = $this->getMockBuilder(Entity::class)
            ->setMethods(['setFoo', 'setBar'])
            ->getMock();

        $entity
            ->expects($this->once())
            ->method('setFoo')
            ->with($this->equalTo(6));

        $entity
            ->expects($this->once())
            ->method('setBar')
            ->with($this->equalTo('John'));

        $mapper = new Mapper;

        $mapper->populateEntity($entity, [
            'foo' => 6,
            'bar' => 'John'
        ]);
    }

    /*
    public function test_Relations_to_Entity_Conversion()
    {
        $params = [
            'id' => 6,
            'created_by' => 2
        ];

        $relations = [
            'createdBy' => Entity::class,
            'nonExistent' => Entity::class
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelationsToEntities($relations, $params);

        $this->assertTrue($newParams['created_by'] instanceof Entity);
    }
    */
}
