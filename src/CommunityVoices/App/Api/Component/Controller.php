<?php

namespace CommunityVoices\App\Api\Component;

class Controller
{
    protected function send404()
    {
        http_response_code(404);
        echo file_get_contents('https://environmentaldashboard.org/404');
        exit;
    }
}
