<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;

class DisplayError
{
    protected $apiProvider;

    public function __construct(
        Component\ApiProvider $apiProvider
    ) {
        $this->apiProvider = $apiProvider;
    }

    public function getError()
    {

    }

    public function getErrors($request)
    {
        // Force access control to process by double querying the first line.
        $this->apiProvider->getJson("/error-log?numLines=1", $request);
    }
}
