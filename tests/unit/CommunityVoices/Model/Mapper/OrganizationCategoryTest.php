<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\OrganizationCategory
 */
class OrganizationCategoryTest extends TestCase
{
    public function test_Retrieving_Organization_Category_By_Id()
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

        $organizationCategory = new Entity\OrganizationCategory;
        $organizationCategory->setId(2);

        $mapper = new OrganizationCategory($pdo);
        $mapper->fetch($organizationCategory);

        $this->assertSame($organizationCategory->getId(), 8);
        $this->assertSame($organizationCategory->getLabel(), 'Foo');
    }

    public function test_Retrieving_Organization_Category_By_Id_Doesnt_Exist()
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

        $organizationCategory = new Entity\OrganizationCategory;
        $organizationCategory->setId(2);

        $mapper = new OrganizationCategory($pdo);
        $mapper->fetch($organizationCategory);

        $this->assertSame($organizationCategory->getId(), null);
    }

    public function test_Creating_Organization_Category()
    {
        $organizationCategory = new Entity\OrganizationCategory;
        $organizationCategory->setLabel('FooBar');

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
                [$this->equalTo(':label'), $this->equalTo($organizationCategory->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($organizationCategory->getType())]
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

        $mapper = new OrganizationCategory($pdo);
        $mapper->save($organizationCategory);
    }

    public function test_Updating_Organization_Category()
    {
        $organizationCategory = new Entity\OrganizationCategory;
        $organizationCategory->setId(4);
        $organizationCategory->setLabel('FooBar');

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
                [$this->equalTo(':id'), $this->equalTo($organizationCategory->getId())],
                [$this->equalTo(':label'), $this->equalTo($organizationCategory->getLabel())],
                [$this->equalTo(':type'), $this->equalTo($organizationCategory->getType())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue(
                $statementByParent
            ));

        $mapper = new OrganizationCategory($pdo);
        $mapper->save($organizationCategory);
    }

    public function test_Deleting_Organization_Category()
    {
        $organizationCategory = new Entity\OrganizationCategory;
        $organizationCategory->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($organizationCategory->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new OrganizationCategory($pdo);
        $mapper->delete($organizationCategory);
    }
}
