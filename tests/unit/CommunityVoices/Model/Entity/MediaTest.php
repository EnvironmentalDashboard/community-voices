<?php

namespace CommunityVoices\Model\Entity;

use PHPUnit\Framework\TestCase;

class MediaTest extends TestCase
{
    public function test_Id_Assignment()
    {
        $instance = new Media;
        $instance->setId(5);

        $this->assertSame($instance->getId(), 5);
    }

    public function test_User_Assignment()
    {
        $user = new User;
        $user->setId(5);

        $instance = new Media;
        $instance->setAddedBy($user);

        $this->assertSame($instance->getAddedBy(), $user);
    }

    public function test_Date_Created_Assignment()
    {
        $now = time();

        $instance = new Media;
        $instance->setDateCreated($now);

        $this->assertSame($instance->getDateCreated(), $now);
    }

    public function test_Type_Assignment()
    {
        $instance = new Media;
        $instance->setType($instance::TYPE_QUOTE);

        $this->assertSame($instance->getType(), $instance::TYPE_QUOTE);
    }

    public function test_Status_Assignment()
    {
        $instance = new Media;
        $instance->setStatus($instance::STATUS_APPROVED);

        $this->assertSame($instance->getStatus(), $instance::STATUS_APPROVED);
    }
}
