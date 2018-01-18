<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

// TODO implement test_toArray

class ImageCollectionTest extends TestCase
{
    public function test_Image_Collection_Type_Generation()
    {
        $imageCollection = new ImageCollection();

        $this->assertSame($imageCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_IMAGE);
    }

    public function test_toArray()
    {
        $instance = new ImageCollection;

        $item0 = $this->createMock(Image::class);
        $item1 = $this->createMock(Image::class);

        $item0->method('toArray')->willReturn(['image' => []]);
        $item1->method('toArray')->willReturn(['image' => []]);

        $instance->addEntity($item0);
        $instance->addEntity($item1);

        $expected=["imageCollection" =>[
            ['image' => []],
            ['image' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
