<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Mapper;

class SlideCollection extends DataMapper
{
    public function fetch(Entity\SlideCollection $slideCollection)
    {
        $this->fetchAll($slideCollection);
    }

    private function fetchAll(Entity\SlideCollection $slideCollection)
    {
        $query = " 	SELECT
						media.id 						AS id,
						media.added_by 					AS addedBy,
						media.date_created 				AS dateCreated,
                        CAST(media.type AS UNSIGNED)    AS type,
                        CAST(media.status AS UNSIGNED)  AS status,
                        slide.content_category_id       AS contentCategoryId,
                        slide.image_id                  AS imageId,
                        slide.quote_id                  AS quoteId,
                        slide.formatted_text            AS formattedText,
                        slide.probability               AS probability,
                        slide.decay_percent             AS decayPercent,
                        slide.decay_start               AS decayStart,
                        slide.decay_end                 AS decayEnd
					FROM
						`community-voices_media` media
					INNER JOIN
						`community-voices_slides` slide
						ON media.id = slide.media_id
		          	WHERE 1
		         "
		         . $this->query_prep($slideCollection->status, "media.status")
                 . $this->query_prep($slideCollection->creators, "media.added_by");

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $imgMapper = new Mapper\Image($this->conn);
            $quoteMapper = new Mapper\Quote($this->conn);
            $entry['image'] = new Entity\Image;
            $entry['image']->setId($entry['imageId']);
            $imgMapper->fetch($entry['image']);
            $entry['quote'] = new Entity\Quote;
            $entry['quote']->setId($entry['quoteId']);
            $quoteMapper->fetch($entry['quote']);
            $contentCategory = new Entity\ContentCategory;
            $contentCategory->setId((int) $entry['contentCategoryId']);
            $entry['ContentCategory'] = $contentCategory;
            $slideCollection->addEntityFromParams($entry);
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
