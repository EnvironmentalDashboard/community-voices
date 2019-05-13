<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;

class DisplayError extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        parent::__construct($secureContainer);
    }

    protected function getError($request)
    {
        $response = new HttpFoundation\JsonResponse(["error" =>
            ["type" => $request->attributes->get('error'), "message" => $request->attributes->get('message')]]);

        return $response;
    }
}
