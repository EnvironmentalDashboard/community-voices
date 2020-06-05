<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class Controller extends Component\SecuredComponent
{
    public function __construct(
        Component\SecureContainer $secureContainer
    ) {
        parent::__construct($secureContainer);
    }

    // Attempts two ways to get the ID of the current element.
    protected function getId($request)
    {
        $id = (int) $request->attributes->get('id');
        if ($id === 0) {
            $id = (int) $request->request->get('id');
        }

        return $id;
    }

    // Takes a string list of form attributes and returns an array of them from the request.
    protected function getFormAttributes($request, $attributes, $useDefaults = true)
    {
        // Sad that PHP doesn't easily allow this process to be entirely functional :(
        $requestAttributes = [];
        foreach ($attributes as $key => $value) {
            $variable = is_string($key) ? $key : $value;
            $default = ($useDefaults && is_string($key)) ? $value : null;

            $requestAttributes[$variable] = $request->request->get($variable) ?? $default;
        }

        return array_filter($requestAttributes, function ($value) {
            return is_array($value) || !is_null($value);
        });
    }

    protected function send404()
    {
        http_response_code(404);
        echo file_get_contents('/404');
        exit;
    }
}
