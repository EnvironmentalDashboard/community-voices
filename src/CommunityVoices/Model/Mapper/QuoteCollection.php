<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class QuoteCollection extends DataMapper
{

    public function attributions(\stdClass $container) {
        $attributions = [];
        foreach ($this->conn->query('SELECT DISTINCT attribution FROM `community-voices_quotes` ORDER BY attribution ASC') as $row) {
            $obj = new \stdClass();
            $obj->attribution = $row['attribution'];
            $attributions[] = $obj;
        }
        $container->allAttributions = $attributions;
    }

    public function fetch(Entity\QuoteCollection $quoteCollection)
    {
        $this->fetchAll($quoteCollection);
    }

    private function fetchAll(Entity\QuoteCollection $quoteCollection)
    {
        $query = " 	SELECT
						media.id 						AS id,
						media.added_by 					AS addedBy,
						media.date_created 				AS dateCreated,
                        CAST(media.type AS UNSIGNED)    AS type,
                        CAST(media.status AS UNSIGNED)  AS status,
                        quote.text                      AS text,
                        quote.attribution               AS attribution,
                        quote.sub_attribution           AS subAttribution,
                        quote.date_recorded             AS dateRecorded,
                        quote.public_document_link      AS publicDocumentLink,
                        quote.source_document_link      AS sourceDocumentLink
					FROM
						`community-voices_media` media
					INNER JOIN
						`community-voices_quotes` quote
						ON media.id = quote.media_id
		          	WHERE 1
		         "
		         . $this->query_prep($quoteCollection->status, "media.status")
                 . $this->query_prep($quoteCollection->creators, "media.added_by");

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $quoteCollection->addEntityFromParams($entry);
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
