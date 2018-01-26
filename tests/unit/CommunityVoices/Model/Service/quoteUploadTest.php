<?php

namespace CommunityVoices\Model\Service;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Component\StateObserver;

class QuoteUploadTest extends TestCase
{
      public function test_Clean_Upload(){

          $sessionMapper = $this->createMock(Mapper\ClientState::class);

          $quoteMapper = $this->createMock(Mapper\Quote::class);

          $quoteMapper
              ->method('save')
              ->will($this->returnCallback(function (HasId $quote) {
                  $quote->setId(7);
              }));

          $mapperFactory = $this->createMock(Component\MapperFactory::class);

          $mapperFactory
              ->method('createClientStateMapper')
              ->with($this->equalTo(Mapper\ClientState::class))
              ->will($this->returnValue($sessionMapper));

          $mapperFactory
              ->method('createDataMapper')
              ->with($this->equalTo(Mapper\Quote::class))
              ->will($this->returnValue($quoteMapper));

          $stateObserver = $this
              ->getMockBuilder(Component\StateObserver::class)
              ->setMethods(['addEntry'])
              ->getMock();

          $quoteUpload = new QuoteUpload($mapperFactory, $stateObserver);

          $this->assertTrue($quoteUpload->newQuote(
              'I always close my eyes when I pee!',
              'Lars Dreith',
              'Oberlin College, 2020',
              'January 24th, 2018',
              '',
              ''
          ));
      }

      public function test_Upload_Missing_Attribution(){

        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $quoteMapper = $this->createMock(Mapper\Quote::class);

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $mapperFactory
            ->method('createDataMapper')
            ->with($this->equalTo(Mapper\Quote::class))
            ->will($this->returnValue($quoteMapper));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry', 'hasEntries', 'hasEntry'])
            ->getMock();

        $stateObserver
            ->method('hasEntry')
            ->will($this->returnValue(true));

        $quoteUpload = new QuoteUpload($mapperFactory, $stateObserver);

        $this->assertFalse($quoteUpload->newQuote(
            'I always close my eyes when I pee!',
            NULL,
            'Oberlin College, 2020',
            'January 24th, 2018',
            '',
            ''
        ));
      }

      /*
       * this test is sketch af
       */
      public function test_Upload_Source_Link_Invalid(){
        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $quoteMapper = $this->createMock(Mapper\Quote::class);

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $mapperFactory
            ->method('createDataMapper')
            ->with($this->equalTo(Mapper\Quote::class))
            ->will($this->returnValue($quoteMapper));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry', 'hasEntries', 'hasEntry'])
            ->getMock();

        $stateObserver
            ->method('hasEntry')
            ->will($this->returnValue(true));

        $quoteUpload = new QuoteUpload($mapperFactory, $stateObserver);

        $this->assertFalse($quoteUpload->newQuote(
            'I always close my eyes when I pee!',
            'Lars Dreith',
            'Oberlin College, 2020',
            'January 24th, 2018',
            'asldkfj',
            ''
        ));
      }

      /*
       * same here
       */
      public function test_Upload_Public_Link_Invalid(){
        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $quoteMapper = $this->createMock(Mapper\Quote::class);

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $mapperFactory
            ->method('createDataMapper')
            ->with($this->equalTo(Mapper\Quote::class))
            ->will($this->returnValue($quoteMapper));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry', 'hasEntries', 'hasEntry'])
            ->getMock();

        $stateObserver
            ->method('hasEntry')
            ->will($this->returnValue(true));

        $quoteUpload = new QuoteUpload($mapperFactory, $stateObserver);

        $this->assertFalse($quoteUpload->newQuote(
            'I always close my eyes when I pee!',
            'Lars Dreith',
            'Oberlin College, 2020',
            'January 24th, 2018',
            '',
            'asldkfj'
        ));
      }
}
