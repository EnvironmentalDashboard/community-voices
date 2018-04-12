<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Group
 */
class GroupTest extends TestCase
{
    public function test_Retrieving_Group_By_Id()
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

        $group = new Entity\Group;
        $group->setId(2);

        $mapper = new Group($pdo);
        $mapper->fetch($group);

        $this->assertSame($group->getId(), 8);
        $this->assertSame($group->getLabel(), 'Foo');
    }

    public function test_Creating_Group()
    {
        $group = new Entity\Group;
        $group->setLabel('Foo');
        $group->setType($group::TYPE_TAG);

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
                [$this->equalTo(':label'), $this->equalTo($group->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($group->getType())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Group($pdo);
        $mapper->save($group);

        $this->assertSame($group->getId(), 2);
    }

    public function test_Updating_Group()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $group = new Entity\Group;
        $group->setId(4);
        $group->setLabel('Bar');
        $group->setType($group::TYPE_TAG);

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
                [$this->equalTo(':label'), $this->equalTo($group->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($group->getType())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Group($pdo);
        $mapper->save($group);
    }

    public function test_Deleting_Group()
    {
        $group = new Entity\Group;
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

        $mapper = new Group($pdo);
        $mapper->delete($group);
    }
}
