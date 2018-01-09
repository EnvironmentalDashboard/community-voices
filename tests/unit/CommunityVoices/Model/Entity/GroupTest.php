<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\StateObserver;
use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Group
 */
class GroupTest extends TestCase
{
    public function provid_Numeric_Assignment()
    {
        return [
            ['5', null],
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

    public function test_If_Valid_Group_Valid_For_Upload()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $instance = new Group;
        $instance->setLabel('foo');

        $this->assertTrue($instance->validateForUpload($stateObserver));
    }

    public function test_If_Invalid_Group_No_Label_Valid_For_Upload()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('label'), $this->equalTo(Group::ERR_LABEL_REQUIRED));

        $instance = new Group;

        $this->assertFalse($instance->validateForUpload($stateObserver));
    }
}
