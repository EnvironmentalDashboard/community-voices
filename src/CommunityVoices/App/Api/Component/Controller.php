<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class Controller extends Component\SecuredComponent
{
    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        parent::__construct($secureContainer);
    }

    protected function send404()
    {
        http_response_code(404);
        echo file_get_contents('https://environmentaldashboard.org/404');
        exit;
    }
}
