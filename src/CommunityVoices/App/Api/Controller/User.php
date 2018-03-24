<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;

class User
{
    protected $registrationService;
    protected $userLookup;
    protected $userManagement;

    public function __construct(
        Service\Registration $registrationService,
        Service\UserLookup $userLookup //,
        // Service\UserManagement $userManagement
    ){
        $this->registrationService = $registrationService;
        $this->userLookup = $userLookup;
        //$this->userLookup = $userManagement;
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

    public function getUser($request)
    {
      var_dump($request);

      $this->userLookup->findById($userId);
    }
}
