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
        return $this->fetchAll($quoteCollection);
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
				 ";

        $statement = $this->conn->prepare($query);

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $quoteCollection->addEntityFromParams($entry);
        }
    }
}
