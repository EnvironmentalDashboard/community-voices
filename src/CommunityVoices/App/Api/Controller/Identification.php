<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;

class Identification extends Component\Controller
{
    protected $secureContainer;
    protected $recognitionAdapter;

    public function __construct(
        Component\SecureContainer $secureContainer,
        Component\RecognitionAdapter $recognitionAdapter
    ) {
        parent::__construct($secureContainer);

        $this->recognitionAdapter = $recognitionAdapter;
    }

    private function getIdentity($request)
    {
    }

    /**
     * User authentication
     */
    private function postLogin($request)
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->get('remember') === 'on';

        $this->recognitionAdapter->authenticate($email, $password, $remember);
    }

    private function postLogout($request)
    {
        $this->recognitionAdapter->logout();
    }
}
