<?php

namespace CommunityVoices\App\Website\Component;

class ApiProvider
{
    public function getJson($path, $request)
    {
        // $cookies = $request->cookies->all();
        //
        // if (count($cookies) > 0) {
        //     $cookieString = "";
        //
        //     foreach ($cookies as $key => $value) {
        //         if (!empty($cookieString))
        //             $cookieString .= "; ";
        //
        //         $cookieString .= "{$key}={$value}";
        //     }
        //
        //     $opts = [
        //         'http' => [
        //             'method' => 'GET',
        //             'header' => "Cookie: {$cookieString}"
        //         ]
        //     ];
        //     $context = stream_context_create($opts);
        // }

        if ($request->cookies->has('userToken')) {
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "Cookie: userToken={$request->cookies->get('userToken')}; PHPSESSID={$request->cookies->get('PHPSESSID')}"
                ]
            ];
            $context = stream_context_create($opts);
        }

        return json_decode(file_get_contents(getenv('API_URL') . $path, false, $context ?? null));
    }

    public function getQueriedJson($path, $request)
    {
        $query = http_build_query($request->query->all());

        return $this->getJson($path . ($query ? '?' . $query : ''), $request);
    }
}
