<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class User
{
    protected $recognitionAdapter;
    protected $mapperFactory;

    protected $userAPIController;

    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Api\Controller\User $userAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->userAPIController = $userAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getProfile($request)
    {
        $apiController = $this->secureContainer->contain($this->userAPIController);
        $apiController->getUser($request);
    }

    public function getProtectedPage($request)
    {
        $apiController = $this->secureContainer->contain($this->userAPIController);

        $apiController->postUser($request);
    }

    public function getRegistration($request)
    {
        // Intentionally Empty
    }

    public function postRegistration($request)
    {
        $apiController = $this->secureContainer->contain($this->userAPIController);

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
        if ($apiController->postUser($request)) {
            $this->recognitionAdapter->authenticate($email, $password, false);
        }
    }

    public function postRegistrationInvite($request)
    {
        $apiController = $this->secureContainer->contain($this->userAPIController);

        $apiController->newToken($request);
    }
}
