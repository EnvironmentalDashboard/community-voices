<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;
use Mock\Entity;
use \InvalidArgumentException;

class MediaCollectionTest extends TestCase
{
    public function provide_Group_Type_Assignment()
    {
        return [
            [MediaCollection::MEDIA_TYPE_QUOTE, MediaCollection::MEDIA_TYPE_QUOTE],
            [MediaCollection::MEDIA_TYPE_QUOTE . '', null], //no strings
            [null, null],
            ['blah', null]
        ];
    }

    /**
     * @dataProvider provide_Group_Type_Assignment
     */
    public function test_Media_Type_Assignment($input, $expected)
    {
        $instance = new MediaCollection;
        $instance->forMediaType($input);

        $this->assertSame($instance->getMediaType(), $expected);
    }

    public function test_toArray()
    {
        $instance = new MediaCollection;

        $item0 = $this->createMock(Media::class);
        $item1 = $this->createMock(Image::class);
        $item2 = $this->createMock(Quote::class);
        $item3 = $this->createMock(Slide::class);

        $item0->method('toArray')->willReturn(['media' => []]);
        $item1->method('toArray')->willReturn(['image' => []]);
        $item2->method('toArray')->willReturn(['quote' => []]);
        $item3->method('toArray')->willReturn(['slide' => []]);

        $instance->addEntity($item0);
        $instance->addEntity($item1);
        $instance->addEntity($item2);
        $instance->addEntity($item3);

        $expected=["mediaCollection" =>[
            ['media' => []],
            ['image' => []],
            ['quote' => []],
            ['slide' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
