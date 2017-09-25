<?php

namespace CommunityVoices\App\Website\View;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;

class Identification
{
    protected $recognitionAdapter;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter)
    {
        $this->recognitionAdapter = $recognitionAdapter;
    }

    public function getLogin($response)
    {
        echo "
            <form action='./authenticate' method='post'>
                Email: <input type='input' name='email'> <br>
                Password: <input type='password' name='password'><input type='submit'>
            </form>";
    }

    /**
     * User registration
     */
    public function postCredentials($response)
    {
        $identity = $this->recognitionAdapter->identify();

        if (!$identity->getId()) {
            echo "Invalid username/password combination";
        } else {
            echo "Welcome, " . $identity->getId();
        }
    }

    public function getLogout()
    {
        echo "Logged out.";
    }
}
