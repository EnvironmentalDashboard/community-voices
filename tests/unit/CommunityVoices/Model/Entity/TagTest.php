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
}
