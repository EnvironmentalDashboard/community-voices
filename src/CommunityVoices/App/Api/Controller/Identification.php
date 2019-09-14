<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component;
use CommunityVoices\App\Api\AccessControl;

class Identification extends Component\Controller
{
    protected $recognitionAdapter;

    public function __construct(
        AccessControl\Identification $identificationAccessControl,

        Component\RecognitionAdapter $recognitionAdapter
    ) {
        parent::__construct($identificationAccessControl);

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
