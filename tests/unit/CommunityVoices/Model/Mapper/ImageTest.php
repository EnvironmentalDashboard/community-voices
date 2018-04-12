<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Image
 */
class ImageTest extends TestCase
{
    public function test_Retrieving_Image_By_Id()
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
                'addedBy' => 6
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $image = new Entity\Image;
        $image->setId(2);

        $mapper = new Image($pdo);
        $mapper->fetch($image);

        $this->assertSame($image->getId(), 8);
        $this->assertEquals($image->getAddedBy()->getId(), 6);
    }

    public function test_Retrieving_Image_By_Id_Doesnt_Exist()
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

        $image = new Entity\Image;
        $image->setId(2);

        $mapper = new Image($pdo);
        $mapper->fetch($image);

        $this->assertSame($image->getId(), null);
    }

    public function test_Creating_Image()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $image = new Entity\Image;
        $image->setAddedBy($creator);
        $image->setStatus($image::STATUS_APPROVED);
        $image->setFileName('foo.jpg');
        $image->setTitle('Title');
        $image->setDescription('This is a description');
        $image->setGeneratedTags('tag1 tag2');
        $image->setDateTaken('5');
        $image->setPhotographer('John Doe');
        $image->setOrganization('Acme Lmtd.');

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
                [$this->equalTo(':added_by'), $this->equalTo($image->getAddedBy()->getId())],
                [$this->equalTo(':date_created'), $this->equalTo(time())],
                [$this->equalTo(':type'), $this->equalTo($image->getType())],
                [$this->equalTo(':status'), $this->equalTo($image->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo(2)],
                [$this->equalTo(':filename'), $this->equalTo($image->getFilename())],
                [$this->equalTo(':generated_tags'), $this->equalTo($image->getGeneratedTags())],
                [$this->equalTo(':title'), $this->equalTo($image->getTitle())],
                [$this->equalTo(':description'), $this->equalTo($image->getDescription())],
                [$this->equalTo(':date_taken'), $this->equalTo($image->getDateTaken())],
                [$this->equalTo(':photographer'), $this->equalTo($image->getPhotographer())],
                [$this->equalTo(':organization'), $this->equalTo($image->getOrganization())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Image($pdo);
        $mapper->save($image);
    }

    public function test_Updating_Image()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $image = new Entity\Image;
        $image->setId(4);
        $image->setAddedBy($creator);
        $image->setStatus($image::STATUS_APPROVED);
        $image->setFileName('foo.jpg');
        $image->setTitle('Title');
        $image->setDescription('This is a description');
        $image->setGeneratedTags('tag1 tag2');
        $image->setDateTaken('5');
        $image->setPhotographer('John Doe');
        $image->setOrganization('Acme Lmtd.');

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
                [$this->equalTo(':id'), $this->equalTo($image->getId())],
                [$this->equalTo(':added_by'), $this->equalTo($image->getAddedBy()->getId())],
                [$this->equalTo(':type'), $this->equalTo($image->getType())],
                [$this->equalTo(':status'), $this->equalTo($image->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo($image->getId())],
                [$this->equalTo(':filename'), $this->equalTo($image->getFilename())],
                [$this->equalTo(':generated_tags'), $this->equalTo($image->getGeneratedTags())],
                [$this->equalTo(':title'), $this->equalTo($image->getTitle())],
                [$this->equalTo(':description'), $this->equalTo($image->getDescription())],
                [$this->equalTo(':date_taken'), $this->equalTo($image->getDateTaken())],
                [$this->equalTo(':photographer'), $this->equalTo($image->getPhotographer())],
                [$this->equalTo(':organization'), $this->equalTo($image->getOrganization())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Image($pdo);
        $mapper->save($image);
    }

    public function test_Deleting_Image()
    {
        $image = new Entity\Image;
        $image->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($image->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Image($pdo);
        $mapper->delete($image);
    }
}
