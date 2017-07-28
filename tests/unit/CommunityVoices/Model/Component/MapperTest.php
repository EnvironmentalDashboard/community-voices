<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Entity\User;
use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Component\Mapper
 */
class MapperTest extends TestCase
{
    public function test_Populating_Entity_Properties()
    {
        $entity = $this->getMockBuilder(User::class)
            ->setMethods(['setId', 'setFirstName', 'setEmail'])
            ->getMock();

        $entity
            ->expects($this->once())
            ->method('setId')
            ->with($this->equalTo(6));

        $entity
            ->expects($this->once())
            ->method('setFirstName')
            ->with($this->equalTo('John'));

        $entity
            ->expects($this->once())
            ->method('setEmail')
            ->with($this->equalTo('foo@bar.com'));

        $mapper = new Mapper;

        $mapper->applyValues($entity, [
            'id' => 6,
            'first_name' => 'John',
            'email' => 'foo@bar.com'
        ]);
    }

    public function test_Relations_to_Entity_Conversion()
    {
        $params = [
            'id' => 6,
            'created_by' => 2
        ];

        $relations = [
            'createdBy' => User::class,
            'nonExistent' => User::class
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelationsToEntities($relations, $params);

        $this->assertTrue($newParams['created_by'] instanceof User);
    }
}
