<?php

namespace CommunityVoices\Model\Entity;

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
}
