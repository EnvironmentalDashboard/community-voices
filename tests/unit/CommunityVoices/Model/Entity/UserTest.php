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
}
