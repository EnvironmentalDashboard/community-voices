<?php

namespace CommunityVoices\App\Website\View;

class Home
{
    public function getLanding($response)
    {
        $response->setBody("Home");
    }
}
