<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ImageCollection extends DataMapper
{
    public function fetch(Entity\ImageCollection $imageCollection)
    {
        return $this->fetchAll($imageCollection);
    }

    private function fetchAll(Entity\ImageCollection $imageCollection)
    {
        $query = " 	SELECT
						media.id 						AS id,
						media.added_by 					AS addedBy,
						media.date_created 				AS dateCreated,
                        CAST(media.type AS UNSIGNED)    AS type,
                        CAST(media.status AS UNSIGNED)  AS status,
                        image.filename                  AS filename,
                        image.generated_tags            AS generatedTags,
                        image.title                     AS title,
                        image.description               AS description,
                        image.date_taken                AS dateTaken,
                        image.photographer              AS photographer,
                        image.organization              AS organization
					FROM
						`community-voices_media` media
					INNER JOIN
						`community-voices_images` image
						ON media.id = image.media_id
				 ";

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }
}
