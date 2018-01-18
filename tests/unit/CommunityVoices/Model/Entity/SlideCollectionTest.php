<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

class SlideCollectionTest extends TestCase
{
    public function test_Slide_Collection_Type_Generation()
    {
        $slideCollection = new SlideCollection();

        $this->assertSame($slideCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_SLIDE);
    }

    public function test_toArray()
    {
        $instance = new SlideCollection;

        $item0 = $this->createMock(Slide::class);
        $item1 = $this->createMock(Slide::class);

        $item0->method('toArray')->willReturn(['slide' => []]);
        $item1->method('toArray')->willReturn(['slide' => []]);

        $instance->addEntity($item0);
        $instance->addEntity($item1);

        $expected=["slideCollection" =>[
            ['slide' => []],
            ['slide' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
