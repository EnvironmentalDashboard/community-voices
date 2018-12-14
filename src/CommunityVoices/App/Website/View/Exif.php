<?php

namespace CommunityVoices\App\Website\View;

class Exif
{
	public function postData ($request)
	{
		return json_encode(exif_read_data($request));
	}
}
>
