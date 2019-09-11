<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Component;

class Identification extends Component\Controller
{
    protected $recognitionAdapter;

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver,

        Component\RecognitionAdapter $recognitionAdapter
    ) {
        parent::__construct($identifier, $logger, $stateObserver);

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
