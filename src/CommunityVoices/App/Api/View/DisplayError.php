<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

class DisplayError
{
    public function getError()
    {
        // Would be better to return what the error actually is.
        $response = new HttpFoundation\Response();
        $response->setStatusCode(500);

        return $response;
    }
}
