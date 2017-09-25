<?php

namespace CommunityVoices\App\Website\View;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;

class User
{
    protected $recognitionAdapter;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter)
    {
        $this->recognitionAdapter = $recognitionAdapter;
    }

    public function getProfile($request)
    {
        $identity = $this->recognitionAdapter->identify();

        if (!$identity->getId()) {
            echo "Not logged in.";
        } else {
            echo "Logged in as " . $identity->getId() . ".";
        }
    }
}
