<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ImageCollection extends DataMapper
{

    // private $status = [
    //     1 => 'pending',
    //     2 => 'rejected',
    //     3 => 'approved'
    // ];

    public function photographers(\stdClass $container) {
        $photographers = [];
        foreach ($this->conn->query('SELECT DISTINCT photographer FROM `community-voices_images` WHERE photographer != "" ORDER BY photographer ASC') as $row) {
            $obj = new \stdClass();
            $obj->photographer = $row['photographer'];
            $photographers[] = $obj;
        }
        $container->photographerCollection = $photographers;
    }

    public function orgs(\stdClass $container) {
        $orgs = [];
        foreach ($this->conn->query('SELECT DISTINCT organization FROM `community-voices_images` WHERE organization != "" ORDER BY organization ASC') as $row) {
            $obj = new \stdClass();
            $obj->org = $row['organization'];
            $orgs[] = $obj;
        }
        $container->orgCollection = $orgs;
    }

    public function fetch(Entity\ImageCollection $imageCollection, int $only_unused, string $search = '', $tags = null, $photographers = null, $orgs = null, int $limit = 5, int $offset = 0, string $order_str = 'id_desc')
    {
        switch ($order_str) {
            case 'id_desc':
                $sort = 'media_id';
                $order = 'DESC';
                break;
            case 'id_asc':
                $sort = 'media_id';
                $order = 'ASC';
                break;
            case 'date_taken_asc':
                $sort = 'date_taken';
                $order = 'ASC';
                break;
            case 'date_taken_desc':
                $sort = 'date_taken';
                $order = 'DESC';
                break;
            case 'photographer_desc':
                $sort = 'photographer';
                $order = 'DESC';
                break;
            default:
                $sort = 'media_id';
                $order = 'DESC';
                break;
        }
        return $this->fetchAll($imageCollection, $only_unused, $search, $tags, $photographers, $orgs, $limit, $offset, $sort, $order);
    }

    private function fetchAll(Entity\ImageCollection $imageCollection, int $only_unused, string $search, $tags, $photographers, $orgs, int $limit, int $offset, $sort = 'date_taken', $order = 'DESC')
    {
        $params = [];

        if ($search == '') {
            $search_query = '';
        } else {
            $search_query = 'AND (title LIKE ? OR description LIKE ? OR photographer LIKE ? OR organization LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
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
        $only_unused_query = '';
        if ($only_unused) {
            $only_unused_query = 'AND media_id NOT IN (SELECT image_id FROM `community-voices_slides` WHERE image_id IS NOT NULL)';
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
                  WHERE 1
                    {$search_query} {$tag_query} {$photographer_query} {$org_query} {$only_unused_query}
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
