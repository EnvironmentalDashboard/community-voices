<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class MediaCollection extends DataMapper
{
	public function fetch(Entity\MediaCollection $mediaCollection)
	{
		return $this->fetchAll($mediaCollection);
	}

	private function fetchAll(Entity\MediaCollection $mediaCollection)
	{
		# developing the query
		"SELECT * FROM `community-voices_media`AS MEDIA INNER JOIN `community-voices_quotes` AS QUOTE ON MEDIA.id = QUOTE.media_id"
	}
}