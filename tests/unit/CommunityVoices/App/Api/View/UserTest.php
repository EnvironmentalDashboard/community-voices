<?php

namespace CommunityVoices\App\Api\View;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Component;

/**
 * @covers CommunityVoices\Model\Entity\User
 */
class UserTest extends TestCase
{
    public function test_Post_User_Registration()
    {
        $stateMapper = $this->createMock(Mapper\ClientState::class);

        $stateMapper
            ->method('retrieve')
            ->will($this->returnValue(false));

        $mapperFactory = $this->createMock(Component\MapperFactory::class);

        $mapperFactory
            ->method('createClientStateMapper')
            ->will($this->returnValue($stateMapper));

        $userView = new User($mapperFactory);

        $this->assertTrue($userView->postRegistration(null));
    }
}
