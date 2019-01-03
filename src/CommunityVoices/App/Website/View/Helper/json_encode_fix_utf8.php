<?php

namespace CommunityVoices\App\Website\View\Helper;

/*
 * This function works as an extension of the default
 * json_encode method.  It will check if the JSON
 * encoding failed due to a UTF-8 error, and if so
 * will automatically sanitize the input.
 */
function json_encode_fix_utf8($data)
{
	$encoded = json_encode($data);

	// If our JSON encoding had an error, we should clean it up
    // UTF-8 wise and try again.
    if (json_last_error() == JSON_ERROR_UTF8) {
        // https://stackoverflow.com/a/46305914/2397924
        $exif_data = mb_convert_encoding($exif_data, "UTF-8", "UTF-8");
        $encoded = json_encode($exif_data);
    }

	return $encoded;
}
