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
}
