<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class DisplayError extends Component\View
{
    const ERRORSLOGPATH = '/var/www/html/log/access.log';

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

    protected function getAllErrors($request, $errors = self::ERRORS_DEFAULT)
    {
        return $errors;

    }
    protected function getSomeErrors($request, $errors = self::ERRORS_DEFAULT)
    {
        return $errors;
    }

}
