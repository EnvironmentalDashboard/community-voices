<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ImageCollection extends DataMapper
{

    private $status = [
        1 => 'pending',
        2 => 'rejected',
        3 => 'approved'
    ];

    public function photographers(\stdClass $container) {
        $photographers = [];
        foreach ($this->conn->query('SELECT DISTINCT photographer FROM `community-voices_images` ORDER BY photographer ASC') as $row) {
            $obj = new \stdClass();
            $obj->photographer = $row['photographer'];
            $photographers[] = $obj;
        }
        $container->allPhotographers = $photographers;
    }

    public function orgs(\stdClass $container) {
        $orgs = [];
        foreach ($this->conn->query('SELECT DISTINCT organization FROM `community-voices_images` WHERE organization != "" ORDER BY organization ASC') as $row) {
            $obj = new \stdClass();
            $obj->org = $row['organization'];
            $orgs[] = $obj;
        }
        $container->allOrgs = $orgs;
    }

    public function fetch(Entity\ImageCollection $imageCollection, string $search = '', $tags = null, $photographers = null, $orgs = null, int $limit = 5, int $offset = 0, int $status = 3)
    {
        return $this->fetchAll($imageCollection, $search, $tags, $photographers, $orgs, $limit, $offset, $status);
    }

    private function fetchAll(Entity\ImageCollection $imageCollection, string $search, $tags, $photographers, $orgs, int $limit, int $offset, int $status, $sort = 'date_taken', $order = 'DESC')
    {
        $params = [];

        if ($search == '') {
            $search_query = '';
        } else {
            $search_query = 'AND (title LIKE ? OR description LIKE ?)';
            $params[] = $search;
            $params[] = $search;
        }
        if ($tags == null) {
            $tag_query = '';
        } else {
            $tag_query = 'AND id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.implode(',', array_map('intval', $tags)).'))';
        }
        if ($photographers == null) {
            $photographer_query = '';
        } else {
            $photographer_query = 'AND photographer IN ('.rtrim(str_repeat('?,', count($photographers)), ',').')';
            foreach ($photographers as $param) {
                $params[] = $param;
            }
        }
        if ($orgs == null) {
            $org_query = '';
        } else {
            $org_query = 'AND organization IN ('.rtrim(str_repeat('?,', count($orgs)), ',').')';
            foreach ($orgs as $param) {
                $params[] = $param;
            }
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS
                    media.id                        AS id,
                    media.added_by                  AS addedBy,
                    media.date_created              AS dateCreated,
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
                    media.status = '{$this->status[$status]}'
                    {$search_query} {$tag_query} {$photographer_query} {$org_query}
                  ORDER BY image.{$sort} {$order}
                  LIMIT {$offset}, {$limit}";

        $statement = $this->conn->prepare($query);
        $statement->execute($params);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $imageCollection->setCount($this->conn->query('SELECT FOUND_ROWS()')->fetchColumn());

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }

}
