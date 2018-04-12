<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class QuoteCollection extends DataMapper
{
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
		         . $this->query_status($quoteCollection->status)
                 . $this->query_creators($quoteCollection->creators);

        var_dump($this->query_status($quoteCollection->status));

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $quoteCollection->addEntityFromParams($entry);
        }
    }

    // quote_creators is a collection of status
    private function query_status($quote_status)
    {
        if (($quote_status == null) || empty($quote_status)) {
            return "";
        } else {
            $toRet = array_map(
            	function($x) {return " media.status='" . $x."'";},
             	$quote_status);
            $toRet = implode(" OR ",$toRet);
            return " AND " . $toRet;
        }
    }

    // quote_creators is a collection of User object
    private function query_creators($quote_creators)
    {
        if (($quote_creators == null) || empty($quote_creators)) {
            return "";
        } else {
            $toRet = " AND ";
            foreach ($quote_creators as $creator) {
                $toRet .= (" media.added_by " . " = " . $creator . " OR");
            }
            $toRet = rtrim($toRet, "OR");
            return $toRet;
        }
    }
}
