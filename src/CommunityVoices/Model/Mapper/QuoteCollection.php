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
		          	WHERE
		            	media.status = :status
				 "
                 . $this->query_creators($quoteCollection->creators);

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':status', $quoteCollection->status);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $quoteCollection->addEntityFromParams($entry);
        }
    }

    // quote_creators is a collection of User object
    private function query_creators($quote_creators)
    {
        if (($quote_creators === null) || empty($quote_creators)) {
            return "";
        } else {
            $toRet = " WHERE ";
            foreach ($quote_creators as $creator) {
                $toRet .= (" media.added_by " . " = " . $creator . " OR");
            }
            $toRet = rtrim($toRet, "OR");
            return $toRet;
        }
    }
}
