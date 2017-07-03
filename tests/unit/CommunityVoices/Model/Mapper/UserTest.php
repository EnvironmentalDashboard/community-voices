<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

class UserTest extends TestCase
{
    public function testRetrievingUserById()
    {
        $pdo = $this
                ->getMockBuilder(PDO::class)
                ->disableOriginalConstructor()
                ->getMock();

        $statement = $this
                        ->getMockBuilder(PDOStatement::class)
                        ->disableOriginalConstructor()
                        ->getMock();

        $statement
            ->method('bindValue')
            ->with($this->equalTo(':id'), $this->equalTo(2));

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([
                'id' => 2,
                'email' => 'foo@blah.com'
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $user = new Entity\User;
        $user->setId(2);

        $mapper = new User($pdo);
        $mapper->fetch($user);
    }

}
