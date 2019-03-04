<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class User
{
    protected $recognitionAdapter;

    protected $userAPIController;
    protected $userAPIView;

    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\Controller\User $userAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
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

        // Grab our username and password from the form.
        $email = $request->request->get('email');
        $password = $request->request->get('password');

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
