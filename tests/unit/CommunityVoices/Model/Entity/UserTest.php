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
}
