<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\StateObserver;
use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers CommunityVoices\Model\Entity\Location
 */
class LocationTest extends TestCase
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
        $instance = new Location;
        $instance->setId($input);

        $this->assertSame($instance->getId(), $expected);
    }

    public function test_Label_Assignment()
    {
        $instance = new Location;
        $instance->setLabel('FooBar');

        $this->assertSame($instance->getLabel(), 'FooBar');
    }

    public function test_Org_Category_Collection_Assignment()
    {
        $orgCatCollection = $this->createMock(GroupCollection::class);

        $instance = new Location;
        $instance->setOrganizationCategoryCollection($orgCatCollection);

        $this->assertSame($instance->getOrganizationCategoryCollection(), $orgCatCollection);
    }

    public function test_Org_Category_Collection_Invalid_Assignment()
    {
        $this->expectException(TypeError::class);

        $orgCatCollection = [];

        $instance = new Location;
        $instance->setOrganizationCategoryCollection($orgCatCollection);
    }

    public function test_Cat_Category_Collection_Assignment()
    {
        $contCatCollection = $this->createMock(GroupCollection::class);

        $instance = new Location;
        $instance->setContentCategoryCollection($contCatCollection);

        $this->assertSame($instance->getContentCategoryCollection(), $contCatCollection);
    }

    public function test_Cat_Category_Collection_Invalid_Assignment()
    {
        $this->expectException(TypeError::class);

        $contCatCollection = [];

        $instance = new Location;
        $instance->setContentCategoryCollection($contCatCollection);
    }

    public function test_If_Valid_Location_Valid_For_Upload()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $instance = new Location;
        $instance->setLabel('foo');

        $this->assertTrue($instance->validateForUpload($stateObserver));
    }

    public function test_If_Invalid_Location_No_Label_Valid_For_Upload()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('label'), $this->equalTo(Location::ERR_LABEL_REQUIRED));

        $instance = new Location;

        $this->assertFalse($instance->validateForUpload($stateObserver));
    }
}
