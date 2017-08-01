<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Location
 */
class LocationTest extends TestCase
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
        $instance = new Media;
        $instance->setId($input);

        $this->assertSame($instance->getId(), $expected);
    }

    public function test_Label_Assignment()
    {
        $instance = new Group;
        $instance->setLabel('FooBar');

        $this->assertSame($instance->getLabel(), 'FooBar');
    }
}
