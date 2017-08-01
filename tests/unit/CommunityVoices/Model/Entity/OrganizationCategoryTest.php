<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\OrganizationCategory
 */
class OrganizationCategoryTest extends TestCase
{
    public function test_OrganizationCategory_Creation()
    {
        $instance = new OrganizationCategory;

        $this->assertSame($instance->getType(), ContentCategory::TYPE_ORG_CAT);
    }
}
