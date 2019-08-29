<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class DisplayError extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory
    ) {
        parent::__construct($mapperFactory);
    }

    public function getError($request)
    {
        $response = new HttpFoundation\JsonResponse(["error" =>
            ["type" => $request->attributes->get('error'), "message" => $request->attributes->get('message')]]);

        return $response;
    }
}
