<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

// TODO implement test_toArray

class QuoteCollectionTest extends TestCase
{
  public function test_Quote_Collection_Type_Generation()
  {
      $quoteCollection = new QuoteCollection();

      $this->assertSame($quoteCollection->getMediaType(), QuoteCollection::MEDIA_TYPE_QUOTE);
  }

/*
  public function test_toArray(){
      // @Frank, pls help
  }*/

}
