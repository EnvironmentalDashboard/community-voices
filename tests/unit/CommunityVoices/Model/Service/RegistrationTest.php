<?php

namespace CommunityVoices\Model\Service;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Mapper;
use Palladium;

/**
 * @covers CommunityVoices\Model\Service\Registration
 */
class RegistrationTest extends TestCase
{
    public function test_Clean_Registration()
    {
        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $userMapper = $this->createMock(Mapper\User::class);

        $userMapper
            ->method('save')
            ->will($this->returnCallback(function (HasId $user) {
                $user->setId(7);
            }));

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $mapperFactory
            ->method('createDataMapper')
            ->with($this->equalTo(Mapper\User::class))
            ->will($this->returnValue($userMapper));

        $pdIdentity = $this->createMock(Palladium\Entity\EmailIdentity::class);

        $pdRegistration = $this->createMock(Palladium\Service\Registration::class);

        $pdRegistration
            ->method('createEmailIdentity')
            ->will($this->returnValue($pdIdentity));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $registration = new Registration($pdRegistration, $mapperFactory, $stateObserver);

        $this->assertTrue($registration->createUser(
            'john@doe.com',
            'password123',
            'password123',
            'John',
            'Doe'
        ));
    }

    public function test_Registration_Invalid_Email()
    {
        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $pdIdentity = $this->createMock(Palladium\Entity\EmailIdentity::class);

        $pdRegistration = $this->createMock(Palladium\Service\Registration::class);

        $pdRegistration
            ->method('createEmailIdentity')
            ->will($this->returnValue($pdIdentity));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry', 'hasEntries', 'hasEntry'])
            ->getMock();

        $stateObserver
            ->method('hasEntry')
            ->will($this->returnValue(true));

        $registration = new Registration($pdRegistration, $mapperFactory, $stateObserver);

        $this->assertFalse($registration->createUser(
            'johndoe.com',
            'password123',
            'password123',
            'John',
            'Doe'
        ));
    }

    public function test_Create_User_Duplicate_Email()
    {
        $sessionMapper = $this->createMock(Mapper\ClientState::class);

        $userMapper = $this->createMock(Mapper\User::class);

        $userMapper
            ->method('existingUserWithEmail')
            ->will($this->returnValue(true));

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->with($this->equalTo(Mapper\ClientState::class))
            ->will($this->returnValue($sessionMapper));

        $mapperFactory
            ->method('createDataMapper')
            ->with($this->equalTo(Mapper\User::class))
            ->will($this->returnValue($userMapper));

        $pdIdentity = $this->createMock(Palladium\Entity\EmailIdentity::class);

        $pdRegistration = $this->createMock(Palladium\Service\Registration::class);

        $pdRegistration
            ->method('createEmailIdentity')
            ->will($this->returnValue($pdIdentity));

        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry', 'hasEntries', 'hasEntry'])
            ->getMock();

        $stateObserver
            ->method('hasEntries')
            ->will($this->returnValue(true));

        $registration = new Registration($pdRegistration, $mapperFactory, $stateObserver);

        $this->assertFalse($registration->createUser(
            'john@doe.com',
            'password123',
            'password123',
            'John',
            'Doe'
        ));
    }
}
