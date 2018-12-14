<?php

namespace CommunityVoices\App\Website\Controller;

class Exif
{
	public function postData ($request)
	{
		return json_encode(exif_read_data($request));
	}
}
>
