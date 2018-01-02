<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Exception\IdentityKnown;
use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\User
 */
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

    public function provide_Role_Assignment()
    {
        return [
            [User::ROLE_GUEST, User::ROLE_GUEST]//,
            //['blah', null]
        ];
    }

    /**
     * @dataProvider provide_Role_Assignment
     */
    public function test_Role_Assignment($input, $expected)
    {
        $instance = new User;
        $instance->setRole($input);

        $this->assertSame($instance->getRole(), $expected);
    }

    public function test_If_Valid_User_Is_Valid_For_Registration()
    {
        $stateObserver = $this->createMock(StateObserver::class);

        $instance = new User;
        $instance->setFirstName('John');
        $instance->setLastName('Doe');
        $instance->setEmail('blah@foo.com');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('foo123');

        $this->assertTrue($instance->validateForRegistration($stateObserver));
    }

    public function test_If_Invalid_User_Bad_Email_Is_Valid_For_Registration()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('email'), $this->equalTo(User::ERR_EMAIL_INVALID));

        $instance = new User;
        $instance->setEmail('invalidemail');
        $instance->setFirstName('John');
        $instance->setLastName('Doe');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('foo123');

        $this->assertFalse($instance->validateForRegistration($stateObserver));
    }

    public function test_If_Invalid_User_Identity_Known_Is_Valid_For_Registration()
    {
        $this->expectException(IdentityKnown::class);

        $stateObserver = $this->createMock(StateObserver::class);

        $instance = new User;
        $instance->setId(6);

        $instance->validateForRegistration($stateObserver);
    }

    public function test_If_Invalid_User_Password_Mismatch_Is_Valid_For_Registration()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('password'), $this->equalTo(User::ERR_PASSWORD_MISMATCH));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setFirstName('John');
        $instance->setLastName('Doe');
        $instance->setPassword('foo123');
        $instance->setConfirmPassword('123foo');

        $this->assertFalse($instance->validateForRegistration($stateObserver));
    }

    public function test_If_Invalid_User_Password_Too_Short_Is_Valid_For_Registration()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('password'), $this->equalTo(User::ERR_PASSWORD_TOO_SHORT));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setFirstName('John');
        $instance->setLastName('Doe');
        $instance->setPassword('123');
        $instance->setConfirmPassword('123');

        $this->assertFalse($instance->validateForRegistration($stateObserver));
    }

    public function test_If_Invalid_User_No_First_Name_Is_Valid_For_Registration()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('firstName'), $this->equalTo(User::ERR_FNAME_REQUIRED));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setLastName('Doe');
        $instance->setPassword('123123');
        $instance->setConfirmPassword('123123');

        $this->assertFalse($instance->validateForRegistration($stateObserver));
    }

    public function test_If_Invalid_User_No_Last_Name_Is_Valid_For_Registration()
    {
        $stateObserver = $this
            ->getMockBuilder(StateObserver::class)
            ->setMethods(['addEntry'])
            ->getMock();

        $stateObserver
            ->expects($this->once())
            ->method('addEntry')
            ->with($this->equalTo('lastName'), $this->equalTo(User::ERR_LNAME_REQUIRED));

        $instance = new User;
        $instance->setEmail('blah@foo.com');
        $instance->setFirstName('Doe');
        $instance->setPassword('123123');
        $instance->setConfirmPassword('123123');

        $this->assertFalse($instance->validateForRegistration($stateObserver));
    }
}
