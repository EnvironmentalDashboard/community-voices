<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;
use CommunityVoices\App\Api\AccessControl;

class DisplayError extends Component\Controller
{
    public function __construct(
        AccessControl\DisplayError $displayErrorAccessControl
    ) {
        parent::__construct($displayErrorAccessControl);
    }

    protected function getError()
    {

    }
}
