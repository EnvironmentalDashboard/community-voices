<?php

namespace CommunityVoices\App\Api\Component;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

/**
 * @covers CommunityVoices\App\Api\Component\Arbiter
 */
class ArbiterTest extends TestCase
{
    public function test_Arbiter_Identity_Check()
    {
        $guest = new Entity\User;
        $guest->setRole(Entity\User::ROLE_GUEST);

        $user = new Entity\User;
        $user->setRole(Entity\User::ROLE_USER);

        $arbiter = new Arbiter(
            [
                "0" => "guest",
                "1" => "user"
            ], [
                ["FuzzyMatch\\AllowAll::*", ["allow" => "guest"]],
                ["FuzzyMatch\\AllowUser::*", ["allow" => "user"]],
                ["ExactMatch\\AllowAll::Method", ["allow" => "guest"]],
                ["ExactMatch\\AllowUser::Method", ["allow" => "user"]]
            ]
        );

        $this->assertTrue($arbiter->isAllowedForIdentity("FuzzyMatch\\AllowAll::Foo", $guest));
        $this->assertTrue($arbiter->isAllowedForIdentity("FuzzyMatch\\AllowAll::Foo", $user));

        $this->assertFalse($arbiter->isAllowedForIdentity("FuzzyMatch\\AllowUser::Foo", $guest));
        $this->assertTrue($arbiter->isAllowedForIdentity("FuzzyMatch\\AllowUser::Foo", $user));

        $this->assertTrue($arbiter->isAllowedForIdentity("ExactMatch\\AllowAll::Method", $guest));
        $this->assertTrue($arbiter->isAllowedForIdentity("ExactMatch\\AllowAll::Method", $user));

        $this->assertFalse($arbiter->isAllowedForIdentity("ExactMatch\\AllowUser::Method", $guest));
        $this->assertTrue($arbiter->isAllowedForIdentity("ExactMatch\\AllowUser::Method", $user));
    }

    public function test_Arbiter_Bad_Signature()
    {
        $guest = new Entity\User;
        $guest->setRole(Entity\User::ROLE_GUEST);

        $arbiter = new Arbiter(
            ["0" => "guest"],
            [
                ["FuzzyMatch\\AllowAll::*", ["allow" => "guest"]]
            ]
        );

        $this->assertFalse($arbiter->isAllowedForIdentity("FuzzyMatch\\BadAllowAll::Foo", $guest));
    }
}
