<?php

namespace CommunityVoices\App\Website\Controller;

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
    }
}
