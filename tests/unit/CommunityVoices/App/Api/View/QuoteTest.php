<?php

namespace CommunityVoices\App\Api\View;

use PHPUnit\Framework\TestCase;

use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Component;

class QuoteTest extends TestCase
{
    public function testPostQuote(){

      $stateMapper = $this->createMock(Mapper\ClientState::class);

      $stateMapper
          ->method('retrieve')
          ->will($this->returnValue(false));

      $mapperFactory = $this->createMock(Component\MapperFactory::class);

      $mapperFactory
          ->method('createClientStateMapper')
          ->will($this->returnValue($stateMapper));

      $quoteView = new Quote($mapperFactory);

      $this->assertTrue($quoteView->postQuote());
    }
}
