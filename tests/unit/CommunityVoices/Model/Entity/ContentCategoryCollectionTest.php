<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\ContentCategoryCollection
 */
class ContentCategoryCollectionTest extends TestCase
{
    public function test_Content_Categry_Collection_Type_Generation()
    {
        $contentCategoryCollection = new ContentCategoryCollection;

        $this->assertSame($contentCategoryCollection->getGroupType(), ContentCategoryCollection::GROUP_TYPE_CONT_CAT);
    }

    public function test_toArray()
    {
        $instance = new ContentCategoryCollection;

        $item0 = $this->createMock(ContentCategory::Class);
        $item1 = $this->createMock(ContentCategory::Class);

        $item0->method('toArray')->willReturn(['contentCategory' => []]);
        $item1->method('toArray')->willReturn(['contentCategory' => []]);
        
        $instance->addEntity($item0);
        $instance->addEntity($item1);

        $expected=["contentCategoryCollection" =>[
            ['contentCategory' => []],
            ['contentCategory' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
