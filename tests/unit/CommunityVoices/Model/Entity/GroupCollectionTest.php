<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;
use Mock\Entity;
use \InvalidArgumentException;

class GroupCollectionTest extends TestCase
{
    public function provide_Group_Type_Assignment()
    {
        return [
            [GroupCollection::GROUP_TYPE_TAG, GroupCollection::GROUP_TYPE_TAG],
            [GroupCollection::GROUP_TYPE_TAG . '', null], //no strings
            [null, null],
            ['blah', null]
        ];
    }

    /**
     * @dataProvider provide_Group_Type_Assignment
     */
    public function test_Group_Type_Assignment($input, $expected)
    {
        $instance = new GroupCollection;
        $instance->forGroupType($input);

        $this->assertSame($instance->getGroupType(), $expected);
    }

    public function provide_Parent_Type_Assignment()
    {
        return [
            [GroupCollection::PARENT_TYPE_LOCATION, GroupCollection::PARENT_TYPE_LOCATION],
            [GroupCollection::PARENT_TYPE_LOCATION . '', null], //no strings
            [null, null],
            ['blah', null]
        ];
    }

    /**
     * @dataProvider provide_Parent_Type_Assignment
     */
    public function test_Parent_Type_Assignment($input, $expected)
    {
        $instance = new GroupCollection;
        $instance->forParentType($input);

        $this->assertSame($instance->getParentType(), $expected);
    }

    public function test_Parent_Assignment()
    {
        $parent = $this->createMock(Media::class);

        $instance = new GroupCollection;
        $instance->forParent($parent);

        $this->assertSame($instance->getParentType(), GroupCollection::PARENT_TYPE_MEDIA);
    }

    public function test_Ensure_Get_Parent_Id_Calls_Parent_Id_Getter()
    {
        $parent = $this->createMock(Media::class);

        $parent
            ->expects($this->exactly(2))
            ->method('getId')
            ->will($this->returnValue(2));

        $instance = new GroupCollection;
        $instance->forParent($parent);

        $instance->getParentId();
    }

    public function test_Parent_Assignment_Invalid_Type()
    {
        $this->expectException(InvalidArgumentException::class);

        $parent = $this->createMock(Entity::class);

        $instance = new GroupCollection;
        $instance->forParent($parent);
    }

    public function test_toArray()
    {
        $instance = new GroupCollection;

        $item0 = $this->createMock(Group::class);
        $item1 = $this->createMock(Tag::class);
        $item2 = $this->createMock(OrganizationCategory::class);
        $item3 = $this->createMock(ContentCategory::class);

        $item0->method('toArray')->willReturn(['group' => []]);
        $item1->method('toArray')->willReturn(['tag' => []]);
        $item2->method('toArray')->willReturn(['organizationCategory' => []]);
        $item3->method('toArray')->willReturn(['contentCategory' => []]);

        $instance->addEntity($item0);
        $instance->addEntity($item1);
        $instance->addEntity($item2);
        $instance->addEntity($item3);

        $expected=["groupCollection" =>[
            ['group' => []],
            ['tag' => []],
            ['organizationCategory' => []],
            ['contentCategory' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
