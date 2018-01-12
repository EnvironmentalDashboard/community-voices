<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\Image
 */
class ImageTest extends TestCase
{
    public function test_Type_Generation()
    {
        $instance = new Image;

        $this->assertSame($instance->getType(), $instance::TYPE_IMAGE);
    }

    public function test_Filename_Assignment()
    {
        $instance = new Image;
        $instance->setFilename('cookieMonster.jpg');

        $this->assertSame($instance->getFilename(), 'cookieMonster.jpg');
    }

    public function test_Title_Assignment()
    {
        $instance = new Image;
        $instance->setTitle('Title for image');
        $this->assertSame($instance->getTitle(), 'Title for image');
    }

    public function test_Description_Assignment()
    {
        $instance = new Image;
        $instance->setDescription('Lorem ipsum');

        $this->assertSame($instance->getDescription(), 'Lorem ipsum');
    }

    public function test_Generated_Tags_Assignment()
    {
        $instance = new Image;
        $instance->setGeneratedTags('cookie chocolate caramel');

        $this->assertSame($instance->getGeneratedTags(), 'cookie chocolate caramel');
    }

    public function provide_Date_Assignment()
    {
        return [
            ['1499970467', 1499970467],
            ['-5', null]
        ];
    }

    /**
     * @dataProvider provide_Date_Assignment
     */
    public function test_Date_Taken_Assignment($input, $expected)
    {
        $instance = new Image;
        $instance->setDateTaken($input);

        $this->assertSame($instance->getDateTaken(), $expected);
    }

    public function test_Photographer_Assignment()
    {
        $instance = new Image;
        $instance->setPhotographer('John Doe');

        $this->assertSame($instance->getPhotographer(), 'John Doe');
    }

    public function test_Organization_Assignment()
    {
        $instance = new Image;
        $instance->setOrganization('Foobar');

        $this->assertSame($instance->getOrganization(), 'Foobar');
    }

    public function test_toArray()
    {
        $instance = new Image;

        $addedBy = $this->createMock(User::class);
        $addedBy->method('getID')
                ->willReturn(true);

        $tagCollection = $this->createMock(GroupCollection::class);

        $instance->setAddedBy($addedBy);
        $instance->setTagCollection($tagCollection);

        $instance->setFilename('cookieMonster.jpg');
        $instance->setTitle('Title for image');
        $instance->setDescription('Lorem ipsum');
        $instance->setGeneratedTags('cookie chocolate caramel');
        $instance->setDateTaken('1499970467');
        $instance->setPhotographer('John Doe');
        $instance->setOrganization('Foobar');

        $expected = ['image' => [
            'id' => null,
            'addedBy' => null,
            'dateCreated' => null,
            'type' => Media::TYPE_IMAGE,
            'status' => null,
            'tagCollection' => null,

            'filename' => 'cookieMonster.jpg',
            'title' => 'Title for image',
            'description' => 'Lorem ipsum',
            'generatedTags' => 'cookie chocolate caramel',
            'dateTaken' => 1499970467,
            'photographer' => 'John Doe',
            'organization' => 'Foobar'
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
