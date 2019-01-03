<?php

namespace CommunityVoices\App\Website\View;
use function Helper\json_encode_fix_utf8;

use CommunityVoices\App\Website\Component;

use Symfony\Component\HttpFoundation;

class Exif extends Component\View
{
    public function postData($request)
    {
        // Turn our Exif data into encoded JSON.
        $image = $request->request->get("image");
        $exif_data = exif_read_data($image);
        $encoded = json_encode_fix_utf8($exif_data);

        // Create the blank response; details filled in later.
        $response = new HttpFoundation\Response();

        // If we had a further error than UTF8 in encoding,
		// report it in response.
        // Otherwise, send along the encoded JSON.
        $last_error = json_last_error();

		// This expands to if $last_error > 0,
		// and feels less ugly than direct comparison.
        if ($last_error) {
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
