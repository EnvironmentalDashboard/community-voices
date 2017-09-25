<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;

class Identification
{
    protected $recognitionAdapter;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter)
    {
        $this->recognitionAdapter = $recognitionAdapter;
    }

    public function getLogin($request)
    {
    }

    /**
     * User registration
     */
    public function postCredentials($request)
    {
        $email = $request->getParameter('email');
        $password = $request->getParameter('password');
        $remember = $request->getParameter('remember');

        $this->recognitionAdapter->authenticate($email, $password, $remember);
    }

    public function getLogout($request)
    {
        $this->recognitionAdapter->logout();
    }
}
