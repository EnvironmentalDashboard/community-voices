<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;

class Identification
{
    protected $recognitionAdapter;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
    }

    public function getIdentity($request)
    {
    }

    /**
     * User authentication
     */
    public function postLogin($request)
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->get('remember') === 'on';

        $this->recognitionAdapter->authenticate($email, $password, $remember);
    }

    public function postLogout($request)
    {
        $this->recognitionAdapter->logout();
    }
}
