<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers CommunityVoices\Model\Entity\Media
 */
class MediaTest extends TestCase
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
        $instance = new Media;
        $instance->setId($input);

        $this->assertSame($instance->getId(), $expected);
    }

    public function test_User_Assignment()
    {
        $user = new User;
        $user->setId(5);

        $instance = new Media;
        $instance->setAddedBy($user);

        $this->assertSame($instance->getAddedBy(), $user);
    }

    public function test_Date_Created_Assignment()
    {
        $now = time();

        $instance = new Media;
        $instance->setDateCreated('1989-07-04 14:00:53');

        $this->assertSame($instance->getDateCreated(), 615578453);
    }

    public function provide_Type_Assignment()
    {
        return [
            [Media::TYPE_SLIDE, Media::TYPE_SLIDE],
            [1, Media::TYPE_SLIDE],
            ['1', Media::TYPE_SLIDE],
            ['foo', null],
            [null, null]
        ];
    }

    /**
     * @dataProvider provide_Type_Assignment
     */
    public function test_Type_Assignment($input, $expected)
    {
        $instance = new Media;
        $instance->setType($input);

        $this->assertSame($instance->getType(), $expected);
    }

    public function provide_Status_Assignment()
    {
        return [
            [Media::STATUS_PENDING, Media::STATUS_PENDING],
            [1, Media::STATUS_PENDING],
            ['1', Media::STATUS_PENDING],
            ['foo', null],
            [null, null]
        ];
    }

    /**
     * @dataProvider provide_Status_Assignment
     */
    public function test_Status_Assignment($input, $expected)
    {
        $instance = new Media;
        $instance->setStatus($input);

        $this->assertSame($instance->getStatus(), $expected);
    }

    public function test_Tag_Collection_Assignment()
    {
        $tagCollection = $this->createMock(GroupCollection::class);

        $instance = new Media;
        $instance->setTagCollection($tagCollection);

        $this->assertSame($instance->getTagCollection(), $tagCollection);
    }

    public function test_Tag_Collection_Invalid_Assignment()
    {
        $this->expectException(TypeError::class);

        $tagCollection = [];

        $instance = new Media;
        $instance->setTagCollection($tagCollection);
    }

    public function test_toArray()
    {
        $instance = new Media;

        $tagCollection = $this->createMock(GroupCollection::class);
        $tagCollection->method('toArray')
                      ->willReturn(['groupCollection' => []]);

        $addedBy = $this->createMock(User::class);
        $addedBy->method('toArray')
                ->willReturn(['user' => [
                    'id' => 8,
                    'email' => null,
                    'firstName' => null,
                    'lastName' => null,
                    'role' => null
                ]]);
        $addedBy->method('getID')
                ->willReturn(8);

        $now = time();
        $instance->setId(5);
        $instance->setAddedBy($addedBy);
        $instance->setDateCreated('1989-06-04 14:00:53');
        $instance->setType(Media::TYPE_IMAGE);
        $instance->setStatus(Media::STATUS_REJECTED);
        $instance->setTagCollection($tagCollection);

        $expected = ['media' => [
            'id' => 5,
            'addedBy' => ['user' => [
                        'id' => 8,
                        'email' => null,
                        'firstName' => null,
                        'lastName' => null,
                        'role' => null
                    ]],
            'dateCreated' => 'Jun 4, 1989',
            'type' => Media::TYPE_IMAGE,
            'status' => "rejected",
            'tagCollection' => ['groupCollection' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }

    public function test_toArray_No_AddedBy()
    {
        $instance = new Media;

        $tagCollection = $this->createMock(GroupCollection::class);
        $tagCollection->method('toArray')
                      ->willReturn(['groupCollection' => []]);

        $now = time();
        $instance->setId(5);
        $instance->setDateCreated('2015-11-14 14:00:53');
        $instance->setType(Media::TYPE_IMAGE);
        $instance->setStatus(Media::STATUS_REJECTED);
        $instance->setTagCollection($tagCollection);

        $expected = ['media' => [
            'id' => 5,
            'addedBy' => null,
            'dateCreated' => 'Nov 14, 2015',
            'type' => Media::TYPE_IMAGE,
            'status' =>"rejected",
            'tagCollection' => ['groupCollection' => []]
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }

    public function test_toArray_No_TagCollection()
    {
        $addedByReturn = ['user' => [
            'id' => 8,
            'email' => null,
            'firstName' => null,
            'lastName' => null,
            'role' => null
        ]];

        $instance = new Media;

        $addedBy = $this->createMock(User::class);

        $addedBy->method('toArray')
                ->willReturn($addedByReturn);

        $addedBy->method('getID')
                ->willReturn(8);

        $now = time();
        $instance->setId(5);
        $instance->setDateCreated('2018-01-19 14:04:13');
        $instance->setType(Media::TYPE_IMAGE);
        $instance->setStatus(Media::STATUS_REJECTED);
        $instance->setAddedBy($addedBy);

        $expected = ['media' => [
            'id' => 5,
            'addedBy' => $addedByReturn,
            'dateCreated' => 'Jan 19, 2018',
            'type' => Media::TYPE_IMAGE,
            'status' => "rejected",
            'tagCollection' => null
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
