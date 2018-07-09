<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ArticleCollection extends DataMapper
{
    public function fetch(Entity\ArticleCollection $articleCollection)
    {
        $this->fetchAll($articleCollection);
    }

    private function fetchAll(Entity\ArticleCollection $articleCollection)
    {
        $query = " 	SELECT
						media.id 						AS id,
						media.added_by 					AS addedBy,
						media.date_created 				AS dateCreated,
                        CAST(media.type AS UNSIGNED)    AS type,
                        CAST(media.status AS UNSIGNED)  AS status,
                        article.image_id                AS image,
                        article.title                   AS title,
                        article.text                    AS text,
                        article.author                  AS author,
                        article.date_recorded           AS dateRecorded
					FROM
						`community-voices_media` media
					INNER JOIN
						`community-voices_articles` article
						ON media.id = article.media_id
		          	WHERE 1
		         "
		         . $this->query_prep($articleCollection->status, "media.status")
                 . $this->query_prep($articleCollection->creators, "media.added_by");

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $image = new Entity\Image;
            $image->setId($entry['image']);
            $entry['image'] = $image;
            $articleCollection->addEntityFromParams($entry);
        }
    }

    // query prepare (turn array into query)
    // $seq is the array
    // $type is the name of the field in database
    private function query_prep($seq, $type)
    {
        if ($seq == null) {
            return "";
        } else {
            $toRet = array_map(
            	function($x) use ($type) {return $type . "='" . $x ."'";},
             	$seq);
            $toRet = implode(" OR ",$toRet);
            return " AND " . $toRet;
        }
    }
}
