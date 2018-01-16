<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

// TODO implement test_toArray

class ImageCollectionTest extends TestCase
{
  public function test_Image_Collection_Type_Generation()
  {
      $imageCollection = new ImageCollection();

      $this->assertSame($imageCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_IMAGE);
  }

  /*
  public function test_toArray(){
      // @Frank, pls help
  }*/

}
