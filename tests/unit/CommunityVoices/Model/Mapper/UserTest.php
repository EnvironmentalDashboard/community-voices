<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;

class UserTest extends TestCase
{
    public function test_Retrieving_User_By_Id()
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

        $this->assertSame($user->getEmail(), 'foo@blah.com');
    }

    public function test_Retrieving_User_By_Email()
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
            ->with($this->equalTo(':email'), $this->equalTo('foo@bah.com'));

        $statement
            ->expects($this->once())
            ->method('fetch')
            ->will($this->returnValue([
                'id' => 2,
                'email' => 'foo@bbah.com'
            ]));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $user = new Entity\User;
        $user->setEmail('foo@bah.com');

        $mapper = new User($pdo);
        $mapper->fetch($user);
    }

    public function test_Registering_User()
    {
        $user = new Entity\User;
        $user->setEmail('foo@bah.com');
        $user->setFirstName('foo');
        $user->setLastName('bah');
        $user->setRole(2);

        $pdo = $this
                ->getMockBuilder(PDO::class)
                ->disableOriginalConstructor()
                ->getMock();

        $pdo
            ->expects($this->once())
            ->method('lastInsertId')
            ->will($this->returnValue(4));

        $statement = $this
                ->getMockBuilder(PDOStatement::class)
                ->disableOriginalConstructor()
                ->getMock();

        $statement
            ->method('bindValue')
            ->withConsecutive(
                [$this->equalTo(':email'), $this->equalTo($user->getEmail())],
                [$this->equalTo(':fname'), $this->equalTo($user->getFirstName())],
                [$this->equalTo(':lname'), $this->equalTo($user->getLastName())],
                [$this->equalTo(':role'), $this->equalTo($user->getRole())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new User($pdo);
        $mapper->save($user);

        $this->assertSame($user->getId(), 4);
    }

    public function test_Updating_User()
    {
        $user = new Entity\User;
        $user->setId(4);
        $user->setEmail('foo@bah.com');
        $user->setFirstName('foo');
        $user->setLastName('bah');
        $user->setRole(2);

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
            ->withConsecutive(
                [$this->equalTo(':id'), $this->equalTo($user->getId())],
                [$this->equalTo(':email'), $this->equalTo($user->getEmail())],
                [$this->equalTo(':fname'), $this->equalTo($user->getFirstName())],
                [$this->equalTo(':lname'), $this->equalTo($user->getLastName())],
                [$this->equalTo(':role'), $this->equalTo($user->getRole())]
            );

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new User($pdo);
        $mapper->save($user);
    }

    public function test_Deleting_User()
    {
        $user = new Entity\User;
        $user->setId(4);

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
            ->with($this->equalTo(':id'), $this->equalTo($user->getId()));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new User($pdo);
        $mapper->delete($user);
    }

    public function test_Existing_User_Check()
    {
        $user = new Entity\User;
        $user->setEmail('john@doe.com');

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
            ->with($this->equalTo(':email'), $this->equalTo($user->getEmail()));

        $statement
            ->method('fetch')
            ->with($this->equalTo(PDO::FETCH_ASSOC))
            ->will($this->returnValue(false));

        $pdo
            ->expects($this->once())
            ->method('prepare')
            ->will($this->returnValue($statement));

        $mapper = new User($pdo);
        $this->assertFalse($mapper->existingUserWithEmail($user));
    }
}
