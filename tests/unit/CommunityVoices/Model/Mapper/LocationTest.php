<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Location
 */
class LocationTest extends TestCase
{
    public function test_Retrieving_Location_By_Id()
    {
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
            ->with($this->equalTo(':id'), $this->equalTo(2));

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([
                'id' => 8,
                'label' => 'Foo'
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $group = new Entity\Location;
        $group->setId(2);

        $mapper = new Location($pdo);
        $mapper->fetch($group);

        $this->assertSame($group->getId(), 8);
        $this->assertSame($group->getLabel(), 'Foo');
    }

    public function test_Retrieving_Quote_By_Id_Doesnt_Exist()
    {
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
            ->with($this->equalTo(':id'), $this->equalTo(2));

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $location = new Entity\Location;
        $location->setId(2);

        $mapper = new Location($pdo);
        $mapper->fetch($location);

        $this->assertSame($location->getId(), null);
    }

    public function test_Creating_Location()
    {
        $group = new Entity\Location;
        $group->setLabel('Foo');

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue(2));

        $statement = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statement
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':label'), $this->equalTo($group->getLabel())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Location($pdo);
        $mapper->save($group);

        $this->assertSame($group->getId(), 2);
    }

    public function test_Updating_Location()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $group = new Entity\Location;
        $group->setId(4);
        $group->setLabel('Bar');

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
                [$this->equalTo(':id'), $this->equalTo($group->getId())],
                [$this->equalTo(':label'), $this->equalTo($group->getLabel())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Location($pdo);
        $mapper->save($group);
    }

    public function test_Deleting_Location()
    {
        $group = new Entity\Location;
        $group->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($group->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Location($pdo);
        $mapper->delete($group);
    }
}
