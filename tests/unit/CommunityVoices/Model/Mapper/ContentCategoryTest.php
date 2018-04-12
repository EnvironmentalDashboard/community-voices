<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\ContentCategory
 */
class ContentCategoryTest extends TestCase
{
    public function test_Retrieving_Content_Category_By_Id()
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

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(2);

        $mapper = new ContentCategory($pdo);
        $mapper->fetch($contentCategory);

        $this->assertSame($contentCategory->getId(), 8);
        $this->assertSame($contentCategory->getLabel(), 'Foo');
    }

    public function test_Retrieving_Content_Category_By_Id_Doesnt_Exist()
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

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(2);

        $mapper = new ContentCategory($pdo);
        $mapper->fetch($contentCategory);

        $this->assertSame($contentCategory->getId(), null);
    }

    public function test_Creating_Content_Category()
    {
        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setLabel('FooBar');
        $contentCategory->setMediaFilename('foo.jpg');

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
                [$this->equalTo(':label'), $this->equalTo($contentCategory->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($contentCategory->getType())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':group_id'), $this->equalTo(2)],
                [$this->equalTo(':media_filename'), $this->equalTo($contentCategory->getMediaFilename())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new ContentCategory($pdo);
        $mapper->save($contentCategory);
    }

    public function test_Updating_Content_Category()
    {
        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(4);
        $contentCategory->setLabel('FooBar');
        $contentCategory->setMediaFilename('foo.jpg');

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
                [$this->equalTo(':id'), $this->equalTo($contentCategory->getId())],
                [$this->equalTo(':label'), $this->equalTo($contentCategory->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($contentCategory->getType())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':group_id'), $this->equalTo($contentCategory->getId())],
                [$this->equalTo(':media_filename'), $this->equalTo($contentCategory->getMediaFilename())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new ContentCategory($pdo);
        $mapper->save($contentCategory);
    }

    public function test_Deleting_Content_Category()
    {
        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($contentCategory->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new ContentCategory($pdo);
        $mapper->delete($contentCategory);
    }
}
