<?php

namespace CommunityVoices\App\Website\View;

use CommunityVoices\App\Website\Component;

class Exif extends Component\View
{
	public function postData ($request)
	{
		var thing = $request->request->get("image");
		var_dump(thing);
		return json_encode(exif_read_data($request->request->get("image"));
	}
}
>
