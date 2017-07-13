<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

class MediaTest extends TestCase
{
    public function test_Retrieving_Media_By_Id()
    {
        $created = strtotime('-2 weeks');

        $media = new Entity\Media;
        $media->setId(2);

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
                'id' => 2,
                'added_by' => 2,
                'date_created' => $created,
                'type' => $media::TYPE_IMAGE,
                'status' => $media::STATUS_APPROVED
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Media($pdo);
        $mapper->fetch($media);

        $expectedAddedBy = new Entity\User;
        $expectedAddedBy->setId(2);

        $this->assertSame($media->getId(), 2);
        $this->assertEquals($media->getAddedBy(), $expectedAddedBy);
        $this->assertSame($media->getStatus(), $media::STATUS_APPROVED);
    }

    public function test_Creating_Media()
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
