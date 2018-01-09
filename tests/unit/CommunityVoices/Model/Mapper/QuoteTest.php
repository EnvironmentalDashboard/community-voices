<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\Model\Mapper\Quote
 */
class QuoteTest extends TestCase
{
    public function test_Retrieving_Quote_By_Id()
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

        $quote = new Entity\Quote;
        $quote->setId(2);

        $mapper = new Quote($pdo);
        $mapper->fetch($quote);

        $this->assertSame($quote->getId(), 8);
        $this->assertEquals($quote->getAddedBy()->getId(), 6);
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

        $quote = new Entity\Quote;
        $quote->setId(2);

        $mapper = new Quote($pdo);
        $mapper->fetch($quote);

        $this->assertSame($quote->getId(), null);
    }

    public function test_Creating_Quote()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $quote = new Entity\Quote;
        $quote->setAddedBy($creator);
        $quote->setStatus($quote::STATUS_APPROVED);
        $quote->setText('Lorem ipsum');
        $quote->setAttribution('John Doe');
        $quote->setDateRecorded(2000);
        $quote->setPublicDocumentLink('http://localhost:1');
        $quote->setSourceDocumentLink('http://localhost:2');

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

        $time = time();

        $statementByParent
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':added_by'), $this->equalTo($quote->getAddedBy()->getId())],
                [$this->equalTo(':date_created'), $this->greaterThan($time - 5) && $this->lessThan($time + 5)],
                [$this->equalTo(':type'), $this->equalTo($quote->getType())],
                [$this->equalTo(':status'), $this->equalTo($quote->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo(2)],
                [$this->equalTo(':text'),
                 $this->equalTo($quote->getText())],
                [$this->equalTo(':attribution'), $this->equalTo($quote->getAttribution())],
                [$this->equalTo(':date_recorded'), $this->equalTo($quote->getDateRecorded())],
                [$this->equalTo(':public_document_link'), $this->equalTo($quote->getPublicDocumentLink())],
                [$this->equalTo(':source_document_link'), $this->equalTo($quote->getSourceDocumentLink())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Quote($pdo);
        $mapper->save($quote);
    }

    public function test_Updating_Quote()
    {
        $creator = new Entity\User;
        $creator->setId(1);

        $quote = new Entity\Quote;
        $quote->setId(2);
        $quote->setAddedBy($creator);
        $quote->setStatus($quote::STATUS_APPROVED);
        $quote->setText('Lorem ipsum');
        $quote->setAttribution('John Doe');
        $quote->setDateRecorded(time());
        $quote->setPublicDocumentLink('http://localhost:1');
        $quote->setSourceDocumentLink('http://localhost:2');

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
                [$this->equalTo(':id'), $this->equalTo($quote->getId())],
                [$this->equalTo(':added_by'), $this->equalTo($quote->getAddedBy()->getId())],
                [$this->equalTo(':type'), $this->equalTo($quote->getType())],
                [$this->equalTo(':status'), $this->equalTo($quote->getStatus())]
            );

        $statementByChild = $this
            ->getMockBuilder(PDOStatement::class)
            ->disableOriginalConstructor()
            ->getMock();

        $statementByChild
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':media_id'), $this->equalTo(2)],
                [$this->equalTo(':text'),
                 $this->equalTo($quote->getText())],
                [$this->equalTo(':attribution'), $this->equalTo($quote->getAttribution())],
                [$this->equalTo(':date_recorded'), $this->equalTo($quote->getDateRecorded())],
                [$this->equalTo(':public_document_link'), $this->equalTo($quote->getPublicDocumentLink())],
                [$this->equalTo(':source_document_link'), $this->equalTo($quote->getSourceDocumentLink())]
            );

        $pdo
            ->expects($this->exactly(2))
            ->method('prepare')
            ->will($this->onConsecutiveCalls(
                $statementByParent,
                $statementByChild
            ));

        $mapper = new Quote($pdo);
        $mapper->save($quote);
    }

    public function test_Deleting_Quote()
    {
        $quote = new Entity\Quote;
        $quote->setId(3);

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
            ->with($this->equalTo(':id'), $this->equalTo($quote->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new Quote($pdo);
        $mapper->delete($quote);
    }
}
