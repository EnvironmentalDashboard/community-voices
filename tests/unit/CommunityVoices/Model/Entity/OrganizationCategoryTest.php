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

    public function test_toArray(){
	    $instance = new OrganizationCategory;

	    $expected = ['organizationCategory' => [
	        'id' => NULL,
	        'label' => NULL,
	        'type' => Group::TYPE_ORG_CAT,
	    ]];

	    $this->assertSame($instance->toArray(), $expected);
	}
}
