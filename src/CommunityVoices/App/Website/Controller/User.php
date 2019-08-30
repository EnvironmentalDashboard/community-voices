<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class User
{
    protected $mapperFactory;
    protected $userAPIController;
    protected $identificationAPIController;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Api\Controller\User $userAPIController,
        Api\Controller\Identification $identificationAPIController
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->userAPIController = $userAPIController;
        $this->identificationAPIController = $identificationAPIController;
    }

    public function getUser($request)
    {
        $this->userAPIController->getUser($request);
    }

    public function postUser($request)
    {
        $this->userAPIController->postUser($request);
    }

    public function getAllUser($request)
    {
        $this->userAPIController->getAllUser($request);
    }

    public function getProtectedPage($request)
    {
        $this->userAPIController->postRegistration($request);
    }

    public function getRegistration($request)
    {
        // Intentionally Empty
    }

    public function postRegistration($request)
    {
        // Grab all of our form elements.
        // Our username and password from the form
        // will be used to log in as the new user if the
        // account is successfully created.
        // All of the elements (besides password) as a whole will be used
        // in order to cache this form for if registration fails.
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');
        $token = $request->request->get('token');

        // Cache the form in case registration fails.
        $form = [
            'email' => $email,
            'firstName' => $firstName,
            'lastName' => $lastName,
            'token' => $token
        ];

        $formCache = new Component\CachedItem('registrationForm');
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);

        // If we successfully create a new user,
        // we can log in as them.
        // If this failed, the error information will be stored
        // in StateObserver->'registration' via the Registration service.
        if ($this->userAPIController->postRegistration($request)) {
            $this->identificationAPIController->postLogin($request);
        }
    }

    public function postRegistrationInvite($request)
    {
        $this->userAPIController->newToken($request);
    }
}
