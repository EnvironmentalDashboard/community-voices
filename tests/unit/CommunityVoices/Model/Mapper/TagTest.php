<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Tag
 */
class TagTest extends TestCase
{
    public function test_Retrieving_Tag_By_Id()
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

        $tag = new Entity\Tag;
        $tag->setId(2);

        $mapper = new Tag($pdo);
        $mapper->fetch($tag);

        $this->assertSame($tag->getId(), 8);
        $this->assertSame($tag->getLabel(), 'Foo');
    }

    public function test_Retrieving_Tag_By_Id_Doesnt_Exist()
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

        $tag = new Entity\Tag;
        $tag->setId(2);

        $mapper = new Tag($pdo);
        $mapper->fetch($tag);

        $this->assertSame($tag->getId(), null);
    }

    public function test_Creating_Tag()
    {
        $tag = new Entity\Tag;
        $tag->setLabel('FooBar');

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue(2));

        $statementByParent = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByParent
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':label'), $this->equalTo($tag->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($tag->getType())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':group_id'), $this->equalTo(2)]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Tag($pdo);
        $mapper->save($tag);
    }

    public function test_Updating_Tag()
    {
        $tag = new Entity\Tag;
        $tag->setId(4);
        $tag->setLabel('FooBar');

        $pdo = $this
            ->getMockBuilder(PDO::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByParent = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByParent
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':id'), $this->equalTo($tag->getId())],
                [$this->equalTo(':label'), $this->equalTo($tag->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($tag->getType())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue(
                $statementByParent
            ));

        $mapper = new Tag($pdo);
        $mapper->save($tag);
    }

    public function test_Deleting_Tag()
    {
        $tag = new Entity\Tag;
        $tag->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($tag->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Tag($pdo);
        $mapper->delete($tag);
    }
}
