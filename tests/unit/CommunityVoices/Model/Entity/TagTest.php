<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Tag
 */
class TagTest extends TestCase
{
    public function test_Tag_Creation()
    {
        $instance = new Tag;

        $this->assertSame($instance->getType(), ContentCategory::TYPE_TAG);
    }

    public function test_toArray()
    {
        $instance = new Tag;
        $instance->setId(7);
        $instance->setLabel('knight');

        $expected = ['tag' => [
            'id' => 7,
            'label' => 'knight',
            'type' => Group::TYPE_TAG
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
