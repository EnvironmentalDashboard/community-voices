<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Notifier;
use CommunityVoices\Model\Exception\IdentityKnown;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testIdAssignment()
    {
        $instance = new User;
        $instance->setId(6);

        $this->assertSame($instance->getId(), 6);
    }

    public function testEmailAssignment()
    {
        $instance = new User;
        $instance->setEmail('john@foo.com');

        $this->assertSame($instance->getEmail(), 'john@foo.com');
    }

    public function testFirstNameAssignment()
    {
        $instance = new User;
        $instance->setFirstName('John');

        $this->assertSame($instance->getFirstName(), 'John');
    }

    public function testLastNameAssignment()
    {
        $instance = new User;
        $instance->setLastName('Doe');

        $this->assertSame($instance->getLastName(), 'Doe');
    }

    public function testRoleAssignment()
    {
        $instance = new User;
        $instance->setRole(User::ROLE_ADMIN);

        $this->assertSame($instance->getRole(), User::ROLE_ADMIN);
    }

    public function testIfValidUserIsValidForRegistration()
    {
        $mockNotifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setEmail('blah@foo.com');

        $this->assertTrue($instance->isValidForRegistration($mockNotifier));
    }

    public function testIfInvalidForForRegistrationInvalidEmail()
    {
        $mockNotifier = $this
                        ->getMockBuilder(Notifier::class)
                        ->setMethods(['addError'])
                        ->getMock();
        $mockNotifier
            ->expects($this->once())
            ->method('addError')
            ->with($this->equalTo('email'), $this->equalTo(User::ERR_INVALID_EMAIL));

        $instance = new User;
        $instance->setEmail('invalidemail');

        $this->assertFalse($instance->isValidForRegistration($mockNotifier));
    }

    public function testIfInvalidForRegistrationIdentityKnown()
    {
        $this->expectException(IdentityKnown::class);

        $mockNotifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setId(6);

        $instance->isValidForRegistration($mockNotifier);
    }
}
