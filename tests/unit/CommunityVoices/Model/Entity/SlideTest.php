<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;
use TypeError;

/**
 * @covers CommunityVoices\Model\Entity\Slide
 */
class SlideTest extends TestCase
{
    /**
     * @TODO content categories should be DO
     */

    public function test_Type_Generation()
    {
        $instance = new Slide;

        $this->assertSame($instance->getType(), $instance::TYPE_SLIDE);
    }

    public function test_Image_Id_Assignment()
    {
        $instance = new Slide;
        $instance->setId(5);

        $this->assertSame($instance->getId(), 5);
    }

    public function test_Content_Category_Assignment()
    {
        $contentCategory = $this->createMock(ContentCategory::class);

        $instance = new Slide;
        $instance->setContentCategory($contentCategory);

        $this->assertSame($instance->getContentCategory(), $contentCategory);
    }

    public function test_Content_Category_Invalid_Assignment()
    {
        $this->expectException(TypeError::class);

        $contentCategory = [];

        $instance = new Slide;
        $instance->setContentCategory($contentCategory);
    }

    public function test_Org_Category_Collection_Assignment()
    {
        $orgCatCollection = $this->createMock(GroupCollection::class);

        $instance = new Slide;
        $instance->setOrganizationCategoryCollection($orgCatCollection);

        $this->assertSame($instance->getOrganizationCategoryCollection(), $orgCatCollection);
    }

    public function test_Org_Category_Collection_Invalid_Assignment()
    {
        $this->expectException(TypeError::class);

        $orgCatCollection = [];

        $instance = new Slide;
        $instance->setOrganizationCategoryCollection($orgCatCollection);
    }

    public function test_Image_Assignment()
    {
        $image = $this->createMock(Image::class);

        $instance = new Slide;
        $instance->setImage($image);

        $this->assertSame($instance->getImage(), $image);
    }

    public function test_Quote_Assignment()
    {
        $quote = $this->createMock(Quote::class);

        $instance = new Slide;
        $instance->setQuote($quote);

        $this->assertSame($instance->getQuote(), $quote);
    }

    public function test_Probability_Assignment()
    {
        $instance = new Slide;
        $instance->setProbability(.3);

        $this->assertSame($instance->getProbability(), .3);
    }

    public function test_Decay_Percent_Assignment()
    {
        $instance = new Slide;
        $instance->setDecayPercent(.3);

        $this->assertSame($instance->getDecayPercent(), .3);
    }

    public function test_Decay_Start_Assignment()
    {
        $time = strtotime('+5 days');

        $instance = new Slide;
        $instance->setDecayStart($time);

        $this->assertSame($instance->getDecayStart(), $time);
    }

    public function test_Decay_End_Assignment()
    {
        $time = strtotime('+5 days');

        $instance = new Slide;
        $instance->setDecayEnd($time);

        $this->assertSame($instance->getDecayEnd(), $time);
    }

    public function test_toArray()
    {
        $instance = new Slide;

        $addedBy = $this->createMock(User::class);
        $addedBy->method('getID')
                ->willReturn(true);
        $addedBy->method('toArray')
                ->willReturn('foo');

        $tagCollection = $this->createMock(GroupCollection::class);
        $tagCollection->method('toArray')
                      ->willReturn('foo');

        $contentCategory = $this->createMock(ContentCategory::class);
        $contentCategory->method('toArray')
                        ->willReturn('foo');

        $image = $this->createMock(Image::class);
        $image->method('toArray')
              ->willReturn('foo');

        $quote = $this->createMock(Quote::class);
        $quote->method('toArray')
              ->willReturn('foo');

        $orgCatCollection = $this->createMock(GroupCollection::class);
        $orgCatCollection->method('toArray')
                        ->willReturn('foo');

        $instance->setAddedBy($addedBy);
        $instance->setTagCollection($tagCollection);

        $instance->setContentCategory($contentCategory);
        $instance->setImage($image);
        $instance->setQuote($quote);
        $instance->setProbability(.3);
        $instance->setDecayPercent(.2);
        $instance->setDecayStart(5);
        $instance->setDecayEnd(7);
        $instance->setOrganizationCategoryCollection($orgCatCollection);

        $expected = ['slide' => [
            'id' => null,
            'addedBy' => 'foo',
            'dateCreated' => null,
            'type' => Media::TYPE_SLIDE,
            'status' => null,
            'tagCollection' => 'foo',

            'contentCategory' => 'foo',
            'image' => 'foo',
            'quote' => 'foo',
            'probability' => .3,
            'decayPercent' => .2,
            'decayStart' => 5,
            'decayEnd' => 7,
            'organizationCategoryCollection' => 'foo'
        ]];

        $this->assertSame($instance->toArray(), $expected);
    }
}
