<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Slide
 */
class SlideTest extends TestCase
{
    public function test_Retrieving_Slide_By_Id()
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
            ->method('fetchAll')
            ->will($this->returnValue([[
                'id' => 8,
                'addedBy' => 6
            ]]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $slide = new Entity\Slide;
        $slide->setId(2);

        $mapper = new Slide($pdo);
        $mapper->fetch($slide);

        $this->assertSame($slide->getId(), 8);
        $this->assertEquals($slide->getAddedBy()->getId(), 6);
    }

    public function test_Creating_Slide()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(2);

        $image = new Entity\Image;
        $image->setId(2);

        $quote = new Entity\Quote;
        $quote->setId(2);

        $slide = new Entity\Slide;
        $slide->setAddedBy($creator);
        $slide->setStatus($slide::STATUS_APPROVED);
        $slide->setContentCategory($contentCategory);
        $slide->setImage($image);
        $slide->setQuote($quote);
        $slide->setProbability(0.5);
        $slide->setDecayPercent(0.2);
        $slide->setDecayStart(strtotime('+2 weeks'));
        $slide->setDecayEnd(strtotime('+4 weeks'));

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
                [$this->equalTo(':added_by'), $this->equalTo($slide->getAddedBy()->getId())],
                [$this->equalTo(':date_created'), $this->equalTo(time())],
                [$this->equalTo(':type'), $this->equalTo($slide->getType())],
                [$this->equalTo(':status'), $this->equalTo($slide->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo(2)],
                [$this->equalTo(':content_category_id'), $this->equalTo($slide->getContentCategory()->getId())],
                [$this->equalTo(':image_id'), $this->equalTo($slide->getImage()->getId())],
                [$this->equalTo(':quote_id'), $this->equalTo($slide->getQuote()->getId())],
                [$this->equalTo(':probability'), $this->equalTo($slide->getProbability())],
                [$this->equalTo(':decay_percent'), $this->equalTo($slide->getDecayPercent())],
                [$this->equalTo(':decay_start'), $this->equalTo($slide->getDecayStart())],
                [$this->equalTo(':decay_end'), $this->equalTo($slide->getDecayEnd())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Slide($pdo);
        $mapper->save($slide);
    }

    public function test_Updating_Slide()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId(2);

        $image = new Entity\Image;
        $image->setId(2);

        $quote = new Entity\Quote;
        $quote->setId(2);

        $slide = new Entity\Slide;
        $slide->setId(4);
        $slide->setAddedBy($creator);
        $slide->setStatus($slide::STATUS_APPROVED);
        $slide->setContentCategory($contentCategory);
        $slide->setImage($image);
        $slide->setQuote($quote);
        $slide->setProbability(0.5);
        $slide->setDecayPercent(0.2);
        $slide->setDecayStart(strtotime('+2 weeks'));
        $slide->setDecayEnd(strtotime('+4 weeks'));

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
                [$this->equalTo(':id'), $this->equalTo($slide->getId())],
                [$this->equalTo(':added_by'), $this->equalTo($slide->getAddedBy()->getId())],
                [$this->equalTo(':type'), $this->equalTo($slide->getType())],
                [$this->equalTo(':status'), $this->equalTo($slide->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo($slide->getId())],
                [$this->equalTo(':content_category_id'), $this->equalTo($slide->getContentCategory()->getId())],
                [$this->equalTo(':image_id'), $this->equalTo($slide->getImage()->getId())],
                [$this->equalTo(':quote_id'), $this->equalTo($slide->getQuote()->getId())],
                [$this->equalTo(':probability'), $this->equalTo($slide->getProbability())],
                [$this->equalTo(':decay_percent'), $this->equalTo($slide->getDecayPercent())],
                [$this->equalTo(':decay_start'), $this->equalTo($slide->getDecayStart())],
                [$this->equalTo(':decay_end'), $this->equalTo($slide->getDecayEnd())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Slide($pdo);
        $mapper->save($slide);
    }

    public function test_Deleting_Slide()
    {
        $slide = new Entity\Slide;
        $slide->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($slide->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Slide($pdo);
        $mapper->delete($slide);
    }
}
