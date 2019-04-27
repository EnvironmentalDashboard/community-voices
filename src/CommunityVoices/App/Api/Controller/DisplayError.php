<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;

class DisplayError extends Component\Controller
{
    /*
     * Note: This is intentionally a public method.
     * Do not make it private!
     * It will cause an infinite loop, as it will be wrapped in a
     * SecureContainer forever.
     */
    public function getError()
    {

    }
}
