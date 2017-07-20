<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Entity\RememberedIdentity;
use PHPUnit\Framework\TestCase;

/**
 * @covers CommunityVoices\Model\Entity\RememberedIdentity
 */
class RememberedIdentityTest extends TestCase
{
    public function test_Account_Id_Assignment()
    {
        $instance = new RememberedIdentity;

        $instance->setAccountId(2);

        $this->assertSame($instance->getAccountId(), 2);
    }

    public function test_Key_Assignment()
    {
        $instance = new RememberedIdentity;

        $instance->setKey(5002);

        $this->assertSame($instance->getKey(), 5002);
    }

    public function test_Series_Assignment()
    {
        $instance = new RememberedIdentity;

        $instance->setSeries(md5('blah'));

        $this->assertSame($instance->getSeries(), md5('blah'));
    }

    public function test_Expires_On_Assignment()
    {
        $instance = new RememberedIdentity;

        $time = time();
        $instance->setExpiresOn($time);

        $this->assertSame($instance->getExpiresOn(), $time);
    }

    public function test_Json_Conversion()
    {
        $instance = new RememberedIdentity;

        $time = time();

        $instance->setAccountId(5);
        $instance->setKey(500);
        $instance->setSeries(md5('foo'));
        $instance->setExpiresOn($time);

        $expected = [
            'accountId' => 5,
            'key' => 500,
            'series' => md5('foo'),
            'expiresOn' => $time
        ];

        $this->assertSame($instance->toJson(), json_encode($expected));
    }

    public function test_Unique_Label_Retrieval()
    {
        $instance = new RememberedIdentity;

        $this->assertSame($instance->getUniqueLabel(), 'rememberedIdentity');
    }
}
