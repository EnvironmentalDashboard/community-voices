<?php

namespace CommunityVoices\App\Website\Component;

class ApiProvider
{
    public function getJson($path)
    {
        return json_decode(file_get_contents(getenv('API_URL') . $path));
    }

    public function getQueriedJson($path, $request)
    {
        $query = http_build_query($request->query->all());

        // if ($request->cookies->has('userToken')) {
        //     $opts = [
        //         'http' => [
        //             'method' => 'GET',
        //             'header' => "Cookie: userToken={$request->cookies->get('userToken')}"
        //         ]
        //     ];
        //     $context = stream_context_create($opts);
        // }

        return $this->getJson($path . ($query ? '?' . $query : ''), $context ?? null);
    }
}
