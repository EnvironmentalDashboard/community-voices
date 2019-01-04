<?php

namespace CommunityVoices\App\Website\View;

use CommunityVoices\App\Website\Component;

use Symfony\Component\HttpFoundation;

class Exif extends Component\View
{
    public function postData($request)
    {
        // Turn our Exif data into encoded JSON.
        $image = $request->request->get("image");
        $exif_data = exif_read_data($image);
        $encoded = json_encode($exif_data);

        // Create the blank response; details filled in later.
        $response = new HttpFoundation\Response();

        // If our JSON encoding had an error, we should clean it up
        // UTF-8 wise and try again.
        if (json_last_error() == JSON_ERROR_UTF8) {
            // https://stackoverflow.com/a/46305914/2397924
            $exif_data = mb_convert_encoding($exif_data, "UTF-8", "UTF-8");
            $encoded = json_encode($exif_data);
        }

        // If we had a further error in encoding, report it in response.
        // Otherwise, send along the encoded JSON.
        $last_error = json_last_error();
        if ($last_error > JSON_ERROR_NONE) {
            // 422 seems most appropriate; ref:
            // https://stackoverflow.com/a/47269747/2397924
            $response->setContent("JSON encoding error " . $last_error);
            $response->setStatusCode(422);
        } else {
            $response->setContent($encoded);
        }

        return $response;
    }
}
