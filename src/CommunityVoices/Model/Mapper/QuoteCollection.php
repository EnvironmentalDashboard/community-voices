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
        foreach ($this->conn->query('SELECT DISTINCT attribution FROM `community-voices_quotes` WHERE attribution != "" ORDER BY attribution ASC') as $row) {
            $obj = new \stdClass();
            $obj->attribution = htmlspecialchars(htmlspecialchars($row['attribution']));
            $attributions[] = $obj;
        }
        $container->allAttributions = $attributions;
    }

    public function fetch(Entity\QuoteCollection $quoteCollection, $only_unused, $search = '', $tags = null, $attributions = null, int $limit, int $offset)
    {
        $this->fetchAll($quoteCollection, $only_unused, $search, $tags, $attributions, $limit, $offset);
    }

    private function fetchAll(Entity\QuoteCollection $quoteCollection, $only_unused, $search, $tags, $attributions, int $limit, int $offset)
    {

        $params = [];
        if ($search == '') {
            $search_query = '';
        } else {
            $search_query = 'AND (text LIKE ? OR attribution LIKE ? OR sub_attribution LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($tags == null) {
            $tag_query = '';
        } else {
            $tag_query = 'AND id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.implode(',', array_map('intval', $tags)).'))';
        }
        if ($attributions == null) {
            $attribution_query = '';
        } else {
            $attribution_query = 'AND attribution IN ('.rtrim(str_repeat('?,', count($attributions)), ',').')';
            foreach ($attributions as $param) {
                $params[] = $param;
            }
        }
        $only_unused_query = '';
        if ($only_unused) {
            $only_unused_query = 'AND id NOT IN (SELECT quote_id FROM `community-voices_slides`)';
        }
        $query = " 	SELECT SQL_CALC_FOUND_ROWS
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
                    {$search_query} {$tag_query} {$attribution_query} {$only_unused_query}
		         "
		         . $this->query_prep($quoteCollection->status, "media.status")
                 . $this->query_prep($quoteCollection->creators, "media.added_by")
                 . " LIMIT {$offset}, {$limit}";

        $statement = $this->conn->prepare($query);

        $statement->execute($params);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $quoteCollection->setCount($this->conn->query('SELECT FOUND_ROWS()')->fetchColumn());

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
            $toRet = '(' .implode(" OR ",$toRet).')';
            return " AND " . $toRet;
        }
    }
}
