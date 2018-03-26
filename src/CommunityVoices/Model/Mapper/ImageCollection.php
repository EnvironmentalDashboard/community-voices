<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ImageCollection extends DataMapper
{
    public function fetch(Entity\ImageCollection $imageCollection, $status = 3)
    {
        if( $status == 3 ){
            return $this->fetchAll($imageCollection);
        } elseif( $status == 2 ) {
            return $this->fetchRejected($imageCollection);
        } elseif( $status == 1 ) {
            return $this->fetchPending($imageCollection);
        }
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
          WHERE
            media.status = 'approved'
				 ";

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }

    private function fetchPending(Entity\ImageCollection $imageCollection)
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
          WHERE
            media.status = 'pending'
				 ";

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }
}
