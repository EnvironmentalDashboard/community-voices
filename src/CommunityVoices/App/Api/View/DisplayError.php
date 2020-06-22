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
    protected function getAllErrors($request)
    {
        $errors = explode('[]',str_replace('\n','',file_get_contents('/var/www/html/log/access.log')));
        foreach($errors as &$item) {
            $item = array("item" => $item);
        }
        $response = new HttpFoundation\JsonResponse(["errors" => $errors]);
        return $response;

    }
}
