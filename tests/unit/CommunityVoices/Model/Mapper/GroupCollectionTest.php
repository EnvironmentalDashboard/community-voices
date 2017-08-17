<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\GroupCollection
 */
class GroupCollectionTest extends TestCase
{
    public function test_Retrieving_Child_Groups_Invalid_Parent_Missing_Id()
    {
        $this->expectException(InvalidArgumentException::class);

        $collection = new Entity\GroupCollection;
        $collection->forGroupType(Entity\GroupCollection::GROUP_TYPE_TAG);
        $collection->forParentType(Entity\GroupCollection::PARENT_TYPE_MEDIA);

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapper = new GroupCollection($pdo);
        $mapper->fetch($collection);
    }

    public function test_Retrieving_Child_Groups_Invalid_Parent_Missing_Parent_Type()
    {
        $this->expectException(InvalidArgumentException::class);

        $collection = new Entity\GroupCollection;
        $collection->forGroupType(Entity\GroupCollection::GROUP_TYPE_TAG);
        $collection->forParentId(2);

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mapper = new GroupCollection($pdo);
        $mapper->fetch($collection);
    }

    public function test_Retrieving_Child_Groups_Of_Media()
    {
        $collection = new Entity\GroupCollection;
        $collection->forGroupType(Entity\GroupCollection::GROUP_TYPE_TAG);
        $collection->forParentType(Entity\GroupCollection::PARENT_TYPE_MEDIA);
        $collection->forParentId(2);

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':type'), $this->equalTo($collection->getGroupType())],
                [$this->equalTo(':mediaId'), $this->equalTo($collection->getParentId())]
            );

        $arrToReturn = [
            ['id' => 8, 'type' => Entity\GroupCollection::GROUP_TYPE_TAG, 'label' => 'Foo'],
            ['id' => 4, 'type' => Entity\GroupCollection::GROUP_TYPE_TAG, 'label' => 'Foo']
        ];

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($arrToReturn));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new GroupCollection($pdo);
        $mapper->fetch($collection);

        foreach ($collection as $key => $entry) {
            $this->assertSame($entry->getId(), $arrToReturn[$key]['id']);
            $this->assertSame($entry->getType(), $arrToReturn[$key]['type']);
            $this->assertSame($entry->getLabel(), $arrToReturn[$key]['label']);
        }
    }

    public function test_Retrieving_Child_Groups_Of_Location()
    {
        $collection = new Entity\GroupCollection;
        $collection->forGroupType(Entity\GroupCollection::GROUP_TYPE_CONT_CAT);
        $collection->forParentType(Entity\GroupCollection::PARENT_TYPE_LOCATION);
        $collection->forParentId(2);

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':type'), $this->equalTo($collection->getGroupType())],
                [$this->equalTo(':locationId'), $this->equalTo($collection->getParentId())]
            );

        $arrToReturn = [
            ['id' => 8, 'type' => Entity\GroupCollection::GROUP_TYPE_CONT_CAT, 'label' => 'Foo'],
            ['id' => 4, 'type' => Entity\GroupCollection::GROUP_TYPE_CONT_CAT, 'label' => 'Foo']
        ];

        $statement
            ->expects($this->once())
            ->method('fetchAll')
            ->will($this->returnValue($arrToReturn));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new GroupCollection($pdo);
        $mapper->fetch($collection);

        foreach ($collection as $key => $entry) {
            $this->assertSame($entry->getId(), $arrToReturn[$key]['id']);
            $this->assertSame($entry->getType(), $arrToReturn[$key]['type']);
            $this->assertSame($entry->getLabel(), $arrToReturn[$key]['label']);
        }
    }
}
