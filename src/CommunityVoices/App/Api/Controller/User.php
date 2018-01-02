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
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        $this->registrationService->createUser($email, $password, $confirmPassword,
            $firstName, $lastName);
    }
}
