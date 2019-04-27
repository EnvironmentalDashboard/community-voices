<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

class DisplayError
{
    public function getError($request)
    {
        $response = new HttpFoundation\JsonResponse(["error" =>
            ["type" => $request->attributes->get('error'), "message" => $request->attributes->get('message')]]);

        return $response;
    }
}
