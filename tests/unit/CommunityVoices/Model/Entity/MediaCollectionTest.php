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
}
