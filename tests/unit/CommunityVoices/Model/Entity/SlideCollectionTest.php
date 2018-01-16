<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

// TODO implement test_toArray

class SlideCollectionTest extends TestCase
{
  public function test_Slide_Collection_Type_Generation()
  {
      $slideCollection = new SlideCollection();

      $this->assertSame($slideCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_SLIDE);
  }

  /*
  public function test_toArray(){
      // @Frank, pls help
  }*/

}
