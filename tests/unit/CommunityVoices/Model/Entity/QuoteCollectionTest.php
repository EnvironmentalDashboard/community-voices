<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

// TODO implement test_toArray

class QuoteCollectionTest extends TestCase
{
    public function test_Quote_Collection_Type_Generation()
    {
        $quoteCollection = new QuoteCollection();

        $this->assertSame($quoteCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_QUOTE);
    }

    public function test_toArray()
    {
        $instance = new QuoteCollection;

        $item0 = $this->createMock(Quote::class);
        $item1 = $this->createMock(Quote::class);

        $item0->method('toArray')->willReturn(['quote' => []]);
        $item1->method('toArray')->willReturn(['quote' => []]);

        $instance->addEntity($item0);
        $instance->addEntity($item1);

        $expected=["quoteCollection" =>[
            ['quote' => []],
            ['quote' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
