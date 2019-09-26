<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class DisplayError extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer, $mapperFactory);
    }

    protected function getError($request)
    {
        $response = new HttpFoundation\JsonResponse(["errors" =>
            [["type" => $request->attributes->get('error'), "message" => $request->attributes->get('message')]]]);

        return $response;
    }
}
