<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\StateObserver;
use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Quote
 */
class QuoteTest extends TestCase
{
    public function test_Text_Assignment()
    {
        $text = "Lorem ipsum dolor sit emet.";

        $instance = new Quote;
        $instance->setText($text);

        $this->assertSame($instance->getText(), $text);
    }

    public function test_Attribution_Assignment()
    {
        $author = "John Doe";

        $instance = new Quote;
        $instance->setAttribution($author);

        $this->assertSame($instance->getAttribution(), $author);
    }

    public function provide_Date_Assignment()
    {
        return [
            ['1499970467', 1499970467],
            ['-5', null]
        ];
    }

    /**
     * @dataProvider provide_Date_Assignment
     */
    public function test_Date_Recorded_Assignment($input, $expected)
    {
        $instance = new Quote;
        $instance->setDateRecorded($input);

        $this->assertSame($instance->getDateRecorded(), $expected);
    }

    public function test_Public_Document_Link_Assignment()
    {
        $link = "http://localhost/";

        $instance = new Quote;
        $instance->setPublicDocumentLink($link);

        $this->assertSame($instance->getPublicDocumentLink(), $link);
    }

    public function test_Source_Document_Link_Assignment()
    {
        $link = "http://localhost/";

        $instance = new Quote;
        $instance->setSourceDocumentLink($link);

        $this->assertSame($instance->getSourceDocumentLink(), $link);
    }

    public function test_If_Valid_Quote_Is_Valid()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $instance = new Quote;
        $instance->setAttribution("John Doe");

        $this->assertTrue($instance->validateForUpload($stateObserver));
    }

    public function test_If_Valid_Quote_Is_Valid_2()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $instance = new Quote;
        $instance->setAttribution("John Doe");
        $instance->setPublicDocumentLink('http://localhost.com');
        $instance->setSourceDocumentLink('http://localhost.com');

        $this->assertTrue($instance->validateForUpload($stateObserver));
    }

    public function test_If_Invalid_Quote_Bad_Source_Document_Is_Valid()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('sourceDocumentLink'), $this->equalTo(Quote::ERR_SOURCE_LINK_INVALID));

        $instance = new Quote;
        $instance->setAttribution("John Doe");
        $instance->setSourceDocumentLink('foo');
        $instance->setPublicDocumentLink('http://localhost.com');

        $this->assertFalse($instance->validateForUpload($stateObserver));
    }

    public function test_If_Invalid_Quote_Bad_Public_Document_Is_Valid()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('publicDocumentLink'), $this->equalTo(Quote::ERR_PUBLIC_LINK_INVALID));

        $instance = new Quote;
        $instance->setAttribution("John Doe");
        $instance->setPublicDocumentLink('foo');
        $instance->setSourceDocumentLink('http://localhost.com');

        $this->assertFalse($instance->validateForUpload($stateObserver));
    }
}
