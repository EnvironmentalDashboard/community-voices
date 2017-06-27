<?php

namespace CommunityVoices\Model\Entity;

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

    public function testHashAssignment()
    {
        $instance = new User;
        $instance->setHash('$2y$10$MMSsjvXzLpHS0.tKjQ3RAOkkYx8cscJAzG9pmdXzVigY.3PPG8zCe');

        $this->assertTrue(password_verify('pass123', $instance->getHash()));
    }

    public function testPasswordAssignment()
    {
        $instance = new User;
        $instance->setPassword('pass123');

        $this->assertTrue(password_verify('pass123', $instance->getHash()));
    }

    public function testPasswordVerification()
    {
        $instance = new User;
        $instance->setPassword('pass123');

        $this->assertTrue($instance->verifyPassword('pass123'));
    }
}
