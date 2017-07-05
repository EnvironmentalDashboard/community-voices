<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Notifier;
use CommunityVoices\Model\Exception\IdentityKnown;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function test_Id_Assignment()
    {
        $instance = new User;
        $instance->setId(6);

        $this->assertSame($instance->getId(), 6);
    }

    public function test_Email_Assignment()
    {
        $instance = new User;
        $instance->setEmail('john@foo.com');

        $this->assertSame($instance->getEmail(), 'john@foo.com');
    }

    public function test_First_Name_Assignment()
    {
        $instance = new User;
        $instance->setFirstName('John');

        $this->assertSame($instance->getFirstName(), 'John');
    }

    public function test_Last_Name_Assignment()
    {
        $instance = new User;
        $instance->setLastName('Doe');

        $this->assertSame($instance->getLastName(), 'Doe');
    }

    public function test_Role_Assignment()
    {
        $instance = new User;
        $instance->setRole(User::ROLE_ADMIN);

        $this->assertSame($instance->getRole(), User::ROLE_ADMIN);
    }

    public function test_If_Valid_User_Is_Valid_For_Registration()
    {
        $mockNotifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setEmail('blah@foo.com');

        $this->assertTrue($instance->isValidForRegistration($mockNotifier));
    }

    public function test_If_Invalid_User_Bad_Email_Is_Valid_For_Registration()
    {
        $mockNotifier = $this
                        ->getMockBuilder(Notifier::class)
                        ->setMethods(['addError'])
                        ->getMock();
        $mockNotifier
            ->expects($this->once())
            ->method('addError')
            ->with($this->equalTo('email'), $this->equalTo(User::ERR_EMAIL_INVALID));

        $instance = new User;
        $instance->setEmail('invalidemail');

        $this->assertFalse($instance->isValidForRegistration($mockNotifier));
    }

    public function test_If_Invalid_User_Identity_Known_Is_Valid_For_Registration()
    {
        $this->expectException(IdentityKnown::class);

        $mockNotifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setId(6);

        $instance->isValidForRegistration($mockNotifier);
    }

}
