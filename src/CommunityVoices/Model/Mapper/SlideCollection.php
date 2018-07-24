<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Mapper;

class SlideCollection extends DataMapper
{
    public function fetch(Entity\SlideCollection $slideCollection, int $limit, int $offset, $order, $search, $tags, $photographers, $orgs, $attributions, array $contentCategories = [])
    {
        $this->fetchAll($slideCollection, $limit, $offset, $order, $search, $tags, $photographers, $orgs, $attributions, $contentCategories);
    }

    private function fetchAll(Entity\SlideCollection $slideCollection, int $limit, int $offset, $order, $search, $tags, $photographers, $orgs, $attributions, array $contentCategories = [])
    {
        $params = [];
        if ($search == '') {
            $search_query = '';
        } else {
            $search_quote_query = 'AND slide.quote_id IN (SELECT `community-voices_quotes`.media_id FROM `community-voices_quotes` WHERE text LIKE ? OR attribution LIKE ? OR sub_attribution LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $search_image_query = 'AND slide.image_id IN (SELECT `community-voices_images`.media_id FROM `community-voices_images` WHERE title LIKE ? OR description LIKE ? OR photographer LIKE ? OR organization LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $search_query = $search_quote_query . ' ' . $search_image_query;
        }
        if ($tags == null) {
            $tag_query = '';
        } else {
            $sanitized_tags = implode(',', array_map('intval', $tags));
            $tag_query = 'AND (slide.quote_id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.$sanitized_tags.')) OR slide.image_id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.$sanitized_tags.')) )';
        }
        if ($attributions == null) {
            $attribution_query = '';
        } else {
            $attribution_query = 'AND slide.quote_id IN (SELECT media_id FROM `community-voices_quotes` WHERE attribution IN ('.rtrim(str_repeat('?,', count($attributions)), ',').'))';
            foreach ($attributions as $param) {
                $params[] = $param;
            }
        }

        if ($photographers == null) {
            $photographer_query = '';
        } else {
            $photographer_query = 'AND slide.image_id IN (SELECT media_id FROM `community-voices_images` WHERE photographer IN ('.rtrim(str_repeat('?,', count($photographers)), ',').'))';
            foreach ($photographers as $param) {
                $params[] = $param;
            }
        }
        if ($orgs == null) {
            $org_query = '';
        } else {
            $org_query = 'AND slide.image_id IN (SELECT media_id FROM `community-voices_images` WHERE organization IN ('.rtrim(str_repeat('?,', count($orgs)), ',').'))';
            foreach ($orgs as $param) {
                $params[] = $param;
            }
        }

        $content_category_query = '1';
        $count = count($contentCategories);
        if ($count === 1) {
            $content_category_query = 'content_category_id = ' . intval($contentCategories[0]);
        } elseif ($count > 1) {
            $content_category_query = 'content_category_id IN (' . implode(',', array_map('intval', $contentCategories)) . ')';
        }
        $query = " 	SELECT SQL_CALC_FOUND_ROWS
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
		          	WHERE {$content_category_query} {$search_query} {$tag_query} {$attribution_query} {$photographer_query} {$org_query}
		         "
		         . $this->query_prep($slideCollection->status, "media.status")
                 . $this->query_prep($slideCollection->creators, "media.added_by")
                 . " LIMIT {$offset}, {$limit}";
                 // echo $query;var_dump($params);die;
        $statement = $this->conn->prepare($query);

        $statement->execute($params);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $slideCollection->setCount($this->conn->query('SELECT FOUND_ROWS()')->fetchColumn());

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
            if ($entry['formattedText'] == '') {
                $entry['formattedText'] = clone $entry['quote'];
            }
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
