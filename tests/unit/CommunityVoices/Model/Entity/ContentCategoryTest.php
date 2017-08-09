<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\ContentCategory
 */
class ContentCategoryTest extends TestCase
{
    public function test_ContentCategory_Creation()
    {
        $instance = new ContentCategory;

        $this->assertSame($instance->getType(), ContentCategory::TYPE_CONT_CAT);
    }

    public function test_MediaFilename_Assignment()
    {
        $instance = new ContentCategory;

        $instance->setMediaFilename('foo.jpg');

        $this->assertSame($instance->getMediaFilename(), 'foo.jpg');
    }

    public function test_Probability_Assignment()
    {
        $instance = new ContentCategory;

        $instance->setProbability(3);

        $this->assertSame($instance->getProbability(), 3);
    }
}
