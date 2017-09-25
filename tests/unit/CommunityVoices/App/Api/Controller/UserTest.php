<?php

namespace CommunityVoices\App\Api\Controller;

use PHPUnit\Framework\TestCase;
use CommunityVoices\Model\Service;

/**
 * @covers CommunityVoices\App\Api\Controller\User
 */
class UserTest extends TestCase
{
    public function test_Post_User_Registration()
    {
        $email = 'foo@foo.blah';
        $password = $confirmPassword = 'fooblah123';
        $firstName = $lastName = "Doe";

        $requestBuilder = new \Fracture\Http\RequestBuilder;

        $request = $requestBuilder->create([
            'get' => [
                'email' => $email,
                'password' => $password,
                'confirmPassword' => $confirmPassword,
                'firstName' => $firstName,
                'lastName' => $lastName
            ]
        ]);

        $registrationService = $this->createMock(Service\Registration::class);

        $registrationService
            ->expects($this->once())
            ->method('createUser')
            ->with($this->equalTo($email),
                    $this->equalTo($password),
                    $this->equalTo($confirmPassword),
                    $this->equalTo($firstName),
                    $this->equalTo($lastName));

        $userController = new User($registrationService);

        $userController->postUser($request);
    }
}
