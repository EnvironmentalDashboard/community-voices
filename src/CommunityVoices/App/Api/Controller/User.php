<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;

class User
{
    protected $registrationService;

    public function __construct(Service\Registration $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * User registration
     */
    public function postUser($request)
    {
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $confirmPassword = $request->getParameter('confirmPassword');
        $firstName = $request->getParameter('firstName');
        $lastName = $request->getParameter('lastName');

        $this->registrationService->createUser($email, $password, $confirmPassword,
            $firstName, $lastName);
    }
}
