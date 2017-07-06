<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Entity\Notifier;
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
        $notifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('foo123');

        $this->assertTrue($instance->validateForRegistration($notifier));
    }

    public function test_If_Invalid_User_Bad_Email_Is_Valid_For_Registration()
    {
        $notifier = $this
                        ->getMockBuilder(Notifier::class)
                        ->setMethods(['addError'])
                        ->getMock();
        $notifier
            ->expects($this->once())
            ->method('addError')
            ->with($this->equalTo('email'), $this->equalTo(User::ERR_EMAIL_INVALID));

        $instance = new User;
        $instance->setEmail('invalidemail');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('foo123');

        $this->assertFalse($instance->validateForRegistration($notifier));
    }

    public function test_If_Invalid_User_Identity_Known_Is_Valid_For_Registration()
    {
        $this->expectException(IdentityKnown::class);

        $notifier = $this->createMock(Notifier::class);

        $instance = new User;
        $instance->setId(6);

        $instance->validateForRegistration($notifier);
    }

    public function test_If_Invalid_User_Password_Mismatch_Is_Valid_For_Registration()
    {
        $notifier = $this
                        ->getMockBuilder(Notifier::class)
                        ->setMethods(['addError'])
                        ->getMock();

        $notifier
            ->expects($this->once())
            ->method('addError')
            ->with($this->equalTo('password'), $this->equalTo(User::ERR_PASSWORD_MISMATCH));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('123foo');

        $this->assertFalse($instance->validateForRegistration($notifier));
    }

    public function test_If_Invalid_User_Password_Too_Short_Is_Valid_For_Registration()
    {
        $notifier = $this
                    ->getMockBuilder(Notifier::class)
                    ->setMethods(['addError'])
                    ->getMock();

        $notifier
            ->expects($this->once())
            ->method('addError')
            ->with($this->equalTo('password'), $this->equalTo(User::ERR_PASSWORD_TOO_SHORT));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setPassword('123');
        $instance->setConfirmPassword('123');

        $this->assertFalse($instance->validateForRegistration($notifier));
    }

}
