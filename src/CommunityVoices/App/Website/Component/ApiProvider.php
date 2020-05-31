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

        if (!empty($request->cookies->all())) {
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "Cookie: " . $_SERVER['HTTP_COOKIE']
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

    public function get($path, $request)
    {
        if (!empty($request->cookies->all())) {
            $opts = [
                'http' => [
                    'method' => 'GET',
                    'header' => "Cookie: " . $_SERVER['HTTP_COOKIE']
                ]
            ];
            $context = stream_context_create($opts);
        }

        return file_get_contents(getenv('API_URL') . $path, false, $context ?? null);
    }

    public function post($path, $request)
    {
        $data = $request->request->all();

        $opts = [
            'http' => [
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nCookie: " . $_SERVER['HTTP_COOKIE'] . "\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ]
        ];
        $context = stream_context_create($opts);

        return file_get_contents(getenv('API_URL') . $path, false, $context);
    }
}
