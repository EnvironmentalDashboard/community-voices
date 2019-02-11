<?php

namespace CommunityVoices\Model\Service;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Component\StateObserver;

class QuoteManagementTest extends TestCase
{
    public function test_Clean_Upload()
    {
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

        $user = new Entity\User;
        $quoteManagement = new QuoteManagement($mapperFactory, $stateObserver);

        $this->assertTrue($quoteManagement->upload(
              'I always close my eyes when I pee!',
              'Lars Dreith',
              'Oberlin College, 2020',
              'January 24th, 2018',
              '',
              '',
              $user
          ));
    }

    public function test_Upload_Missing_Attribution()
    {
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

        $user = new Entity\User;
        $quoteManagement = new QuoteManagement($mapperFactory, $stateObserver);

        $this->assertFalse($quoteManagement->upload(
            'I always close my eyes when I pee!',
            null,
            'Oberlin College, 2020',
            'January 24th, 2018',
            '',
            '',
            $user
        ));
    }

    /*
     * this test is sketch af
     */
    public function test_Upload_Source_Link_Invalid()
    {
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

        $user = new Entity\User;

        $quoteManagement = new QuoteManagement($mapperFactory, $stateObserver);

        $this->assertFalse($quoteManagement->upload(
            'I always close my eyes when I pee!',
            'Lars Dreith',
            'Oberlin College, 2020',
            'January 24th, 2018',
            'asldkfj',
            '',
            $user
        ));
    }

    /*
     * same here
     */
    public function test_Upload_Public_Link_Invalid()
    {
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

        $user = new Entity\User;

        $quoteManager = new QuoteManagement($mapperFactory, $stateObserver);

        $this->assertFalse($quoteManager->upload(
            'I always close my eyes when I pee!',
            'Lars Dreith',
            'Oberlin College, 2020',
            'January 24th, 2018',
            '',
            'asldkfj',
            $user
        ));
    }
}
