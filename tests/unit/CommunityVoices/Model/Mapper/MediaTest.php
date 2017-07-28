<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Media
 */
class MediaTest extends TestCase
{
    public function test_Retrieving_Media_By_Id()
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
                'added_by' => 6
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $media = new Entity\Media;
        $media->setId(2);

        $mapper = new Media($pdo);
        $mapper->fetch($media);

        $this->assertSame($media->getId(), 8);
        $this->assertEquals($media->getAddedBy()->getId(), 6);
    }

    public function test_Saving_Media()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $media = new Entity\Media;
        $media->setAddedBy($creator);
        $media->setType($media::TYPE_QUOTE);
        $media->setStatus($media::STATUS_PENDING);

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
                [$this->equalTo(':added_by'), $this->equalTo($media->getAddedBy()->getId())],
                [$this->equalTo(':date_created'), $this->equalTo(time())],
                [$this->equalTo(':type'), $this->equalTo($media->getType())],
                [$this->equalTo(':status'), $this->equalTo($media->getStatus())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Media($pdo);
        $mapper->save($media);

        $this->assertSame($media->getId(), 2);
    }

    public function test_Updating_Media()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $media = new Entity\Media;
        $media->setId(4);
        $media->setAddedBy($creator);
        $media->setType($media::TYPE_QUOTE);
        $media->setStatus($media::STATUS_PENDING);

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
                [$this->equalTo(':id'), $this->equalTo($media->getId())],
                [$this->equalTo(':added_by'), $this->equalTo($media->getAddedBy()->getId())],
                [$this->equalTo(':type'), $this->equalTo($media->getType())],
                [$this->equalTo(':status'), $this->equalTo($media->getStatus())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Media($pdo);
        $mapper->save($media);
    }
}
