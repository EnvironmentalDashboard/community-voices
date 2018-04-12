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

    public function test_Entity_Relationship_Conversion()
    {
        $params = [
            'id' => 6,
            'addedBy' => 2,
        ];

        $relations = [
            'Entity' => [
                'addedBy' => [
                    'class' => Entity::class,
                    'attributes' => [
                        'id' => 'addedBy'
                    ]
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);

        $expectedParams = [
            'addedBy' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Entity_Relationship_Conversion_With_Static_Attr()
    {
        $params = [
            'id' => 6,
            'addedBy' => 2,
        ];

        $relations = [
            'Entity' => [
                'addedBy' => [
                    'class' => Entity::class,
                    'attributes' => [
                        'id' => 'addedBy'
                    ],
                    'static' => [
                        'foo' => 'bar'
                    ]
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);
        $expectedEntity->setFoo('bar');

        $expectedParams = [
            'addedBy' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Entity_Relationship_Conversion_With_Missing_Attr()
    {
        $params = [
            'id' => 6,
            'addedBy' => 2,
        ];

        $relations = [
            'Entity' => [
                'addedBy' => [
                    'class' => Entity::class,
                    'attributes' => [
                        'id' => 'addedBy',
                        'blah' => 'foo' // this is msising from $params
                    ]
                ]
            ]
        ];

        $expectedEntity = new Entity;
        $expectedEntity->setId(2);

        $expectedParams = [
            'addedBy' => $expectedEntity
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Collection_Relationship_Conversion()
    {
        $params = [
            'id' => 6
        ];

        $relations = [
            'Collection' => [
                'tagCollection' => [
                    'class' => EntityCollection::class,
                    'attributes' => [
                        'parentId' => 'id'
                    ]
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;
        $expectedCollection->forParentId(6);

        $expectedParams = [
            'tagCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }

    public function test_Collection_Relationship_Conversion_With_Static_Attr()
    {
        $params = [
            'id' => 6
        ];

        $relations = [
            'Collection' => [
                'tagCollection' => [
                    'class' => EntityCollection::class,
                    'attributes' => [
                        'parentId' => 'id'
                    ],
                    'static' => [
                        'parentType' => 2
                    ]
                ]
            ]
        ];

        $expectedCollection = new EntityCollection;
        $expectedCollection->forParentId(6);
        $expectedCollection->forParentType(2);

        $expectedParams = [
            'tagCollection' => $expectedCollection
        ];

        $mapper = new Mapper;

        $newParams = $mapper->convertRelations($relations, $params);

        $this->assertEquals($newParams, $expectedParams);
    }
}
