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

    protected function getIdentity($request)
    {
    }

    /**
     * User authentication
     */
    protected function postLogin($request)
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->get('remember') === 'on';

        $this->recognitionAdapter->authenticate($email, $password, $remember);
    }

    protected function postLogout($request)
    {
        $this->recognitionAdapter->logout();
    }
}
