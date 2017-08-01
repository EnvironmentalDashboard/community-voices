<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Group
 */
class GroupTest extends TestCase
{
    public function provid_Numeric_Assignment()
    {
        return [
            ['5', 5],
            [null, null],
            [5, 5],
            ['ipsum', null]
        ];
    }

    /**
     * @dataProvider provid_Numeric_Assignment
     */
    public function test_Id_Assignment($input, $expected)
    {
        $instance = new Group;
        $instance->setId($input);

        $this->assertSame($instance->getId(), $expected);
    }

    public function provide_Type_Assignment()
    {
        return [
            [Group::TYPE_TAG, Group::TYPE_TAG],
            [1, Group::TYPE_TAG],
            ['1', Group::TYPE_TAG],
            ['foo', null],
            [null, null]
        ];
    }

    /**
     * @dataProvider provide_Type_Assignment
     */
    public function test_Type_Assignment($input, $expected)
    {
        $instance = new Group;
        $instance->setType($input);

        $this->assertSame($instance->getType(), $expected);
    }

    public function test_Label_Assignment()
    {
        $instance = new Group;
        $instance->setLabel('FooBar');

        $this->assertSame($instance->getLabel(), 'FooBar');
    }
}
