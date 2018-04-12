<?php

namespace CommunityVoices\App\Website\Component\Mapper;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Contract\Cookieable;
use Mock\Response;

/**
 * @covers CommunityVoices\App\Website\Component\Mapper\Cookie
 */
class CookieTest extends TestCase
{
    public function test_Saving_Cookie()
    {
        $cookieableInstance = $this
            ->getMockBuilder(Cookieable::class)
            ->disableOriginalConstructor()
            ->getMock();

        $cookieableInstance
            ->method('getUniqueLabel')
            ->will($this->returnValue('foo'));

        $cookieableInstance
            ->method('toJSON')
            ->will($this->returnValue('{}'));

        $expires = time();

        $cookieableInstance
            ->method('getExpiresOn')
            ->will($this->returnValue($expires));

        $response = $this
            ->getMockBuilder(Response::class)
            ->setMethods(['addCookie'])
            ->getMock();

        $response
            ->expects($this->once())
            ->method('addCookie')
            ->with(
                $this->equalTo($cookieableInstance->getUniqueLabel()),
                $this->equalTo($cookieableInstance->toJSON()),
                $this->equalTo(['expires' => $cookieableInstance->getExpiresOn()])
            );

        $mapper = new Cookie(null, $response);
        $mapper->save($cookieableInstance);
    }

    public function test_Fetching_Cookie()
    {
        $cookieableInstance = $this
            ->getMockBuilder(Cookieable::class)
            ->setMethods(['getUniqueLabel', 'toJSON', 'getExpiresOn', 'setFoo'])
            ->getMock();

        $cookieableInstance
            ->method('getUniqueLabel')
            ->will($this->returnValue('foo'));

        $cookieableInstance
            ->expects($this->once())
            ->method('setFoo')
            ->with($this->equalTo('bar'));

        $request = $this
            ->getMockBuilder(Request::class)
            ->setMethods(['getCookie'])
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getCookie')
            ->will($this->returnValue('{"foo": "bar"}'));

        $mapper = new Cookie($request, null);
        $mapper->fetch($cookieableInstance);
    }

    public function test_Fetching_Non_Existent_Cookie()
    {
        $cookieableInstance = $this
            ->getMockBuilder(Cookieable::class)
            ->setMethods(['getUniqueLabel', 'toJSON', 'getExpiresOn', 'setFoo'])
            ->getMock();

        $cookieableInstance
            ->method('getUniqueLabel')
            ->will($this->returnValue('foo'));

        $cookieableInstance
            ->expects($this->never())
            ->method('setFoo')
            ->with($this->equalTo('bar'));

        $request = $this
            ->getMockBuilder(Request::class)
            ->setMethods(['getCookie'])
            ->getMock();

        $request
            ->expects($this->once())
            ->method('getCookie')
            ->will($this->returnValue(false));

        $mapper = new Cookie($request, null);
        $mapper->fetch($cookieableInstance);
    }

    public function test_Deleting_Cookie()
    {
        $cookieableInstance = $this
            ->getMockBuilder(Cookieable::class)
            ->getMock();

        $cookieableInstance
            ->expects($this->once())
            ->method('getUniqueLabel')
            ->will($this->returnValue('foobar'));

        $response = $this
            ->getMockBuilder(Request::class)
            ->setMethods(['removeCookie'])
            ->getMock();

        $response
            ->expects($this->once())
            ->method('removeCookie')
            ->will($this->returnValue('foobar'));

        $mapper = new Cookie(null, $response);
        $mapper->delete($cookieableInstance);
    }
}
