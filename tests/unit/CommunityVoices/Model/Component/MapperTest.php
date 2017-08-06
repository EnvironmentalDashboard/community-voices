<?php

namespace CommunityVoices\Model\Component;

use CommunityVoices\Model\Component\Mapper;
use CommunityVoices\Model\Entity\User;
use PHPUnit\Framework\TestCase;
use Mock\Entity;
use Mock\EntityCollection;

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

    public function test_Single_Cardinality_Relationship_Conversion()
    {
        $params = [
            'id' => 6,
            'entityId' => 2,
        ];

        $relations = [
            'entity' => [
                'class' => Entity::class,
                'attributes' => [
                    'id' => 'entityId'
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);

        $expectedParams = [
            'entity' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeSingleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Single_Cardinality_Relationship_Conversion_Multiple_Attributes()
    {
        $params = [
            'id' => 6,
            'entityId' => 2,
            'entityFoo' => 'lorem'
        ];

        $relations = [
            'entity' => [
                'class' => Entity::class,
                'attributes' => [
                    'id' => 'entityId',
                    'foo' => 'entityFoo'
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);
        $expectedEntity->setFoo('lorem');

        $expectedParams = [
            'entity' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeSingleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Single_Cardinality_Relationship_Conversion_Extra_Attributes()
    {
        $params = [
            'id' => 6,
            'entityId' => 2,
            'entityFoo' => 'lorem'
        ];

        $relations = [
            'entity' => [
                'class' => Entity::class,
                'attributes' => [
                    'id' => 'entityId',
                    'foo' => 'entityFoo',
                    'bar' => 'entityBar' //extra attribute; not in $params
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);
        $expectedEntity->setFoo('lorem');

        $expectedParams = [
            'entity' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeSingleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Multiple_Cardinality_Relationship_Conversion()
    {
        $params = [
            ['id' => 6, 'entityId' => 2],
            ['id' => 6, 'entityId' => 3],
            ['id' => 6, 'entityId' => 7]
        ];

        $relations = [
            'entityCollection' => [
                'class' => EntityCollection::class,
                'attributes' => [
                    'id' => 'entityId'
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;

        foreach ($params as $key => $value) {
            $expectedCollection->addEntityFromParams([
                'id' => $value['entityId']
            ]);
        }

        $expectedParams = [
            'entityCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeMultipleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Multiple_Cardinality_Relationship_Conversion_Multiple_Attributes()
    {
        $params = [
            ['id' => 6, 'entityId' => 2, 'entityFoo' => 'lorem'],
            ['id' => 6, 'entityId' => 3, 'entityFoo' => 'ipsum'],
            ['id' => 6, 'entityId' => 7, 'entityFoo' => 'dolor']
        ];

        $relations = [
            'entityCollection' => [
                'class' => EntityCollection::class,
                'attributes' => [
                    'id' => 'entityId',
                    'foo' => 'entityFoo'
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;

        foreach ($params as $key => $value) {
            $expectedCollection->addEntityFromParams([
                'id' => $value['entityId'],
                'foo' => $value['entityFoo']
            ]);
        }

        $expectedParams = [
            'entityCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeMultipleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Multiple_Cardinality_Relationship_Conversion_Extra_Attributes()
    {
        $params = [
            ['id' => 6, 'entityId' => 2, 'entityFoo' => 'lorem'],
            ['id' => 6, 'entityId' => 3, 'entityFoo' => 'ipsum'],
            ['id' => 6, 'entityId' => 7, 'entityFoo' => 'dolor']
        ];

        $relations = [
            'entityCollection' => [
                'class' => EntityCollection::class,
                'attributes' => [
                    'id' => 'entityId',
                    'foo' => 'entityFoo',
                    'bar' => 'entityBar'
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;

        foreach ($params as $key => $value) {
            $expectedCollection->addEntityFromParams([
                'id' => $value['entityId'],
                'foo' => $value['entityFoo']
            ]);
        }

        $expectedParams = [
            'entityCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeMultipleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Multiple_Cardinality_Relationship_Conversion_Empty_Item()
    {
        $params = [
            ['id' => 6, 'entityId' => 2, 'entityFoo' => 'lorem'],
            ['id' => 6, 'entityId' => 3, 'entityFoo' => 'ipsum'],
            ['id' => 6] //empty item
        ];

        $relations = [
            'entityCollection' => [
                'class' => EntityCollection::class,
                'attributes' => [
                    'id' => 'entityId',
                    'foo' => 'entityFoo',
                    'bar' => 'entityBar'
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;

        foreach ($params as $key => $value) {
            if (array_key_exists('entityId', $value) && array_key_exists('entityFoo', $value)) {
                $expectedCollection->addEntityFromParams([
                    'id' => $value['entityId'],
                    'foo' => $value['entityFoo']
                ]);
            }
        }

        $expectedParams = [
            'entityCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->makeMultipleCardinalityRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }
}
