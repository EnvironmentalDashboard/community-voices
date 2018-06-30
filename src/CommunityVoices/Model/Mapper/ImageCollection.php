<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ImageCollection extends DataMapper
{

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

    public function fetch(Entity\ImageCollection $imageCollection, int $limit = 5, int $offset = 0, $sort = 'date_taken', $order = 'DESC', int $status = 3)
    {
        if( $status == 3 ){
            return $this->fetchAll($imageCollection, $limit, $offset);
        } elseif( $status == 2 ) {
            return $this->fetchRejected($imageCollection, $limit, $offset);
        } elseif( $status == 1 ) {
            return $this->fetchPending($imageCollection, $limit, $offset);
        }
    }

    private function fetchAll(Entity\ImageCollection $imageCollection, int $limit = 5, int $offset = 0, $sort = 'date_taken', $order = 'DESC')
    {
        if (!is_int($limit)) {
            $limit = 5;
        }
        if (!is_int($offset)) {
            $offset = 0;
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
                    media.status = 'approved'
                  ORDER BY image.{$sort} {$order}
                  LIMIT {$offset}, {$limit}"; // $offset, $limit, $sort, $order sanitized by Image API Controller


        $statement = $this->conn->prepare($query);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        $imageCollection->setCount($this->conn->query('SELECT FOUND_ROWS()')->fetchColumn());

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }

    private function fetchPending(Entity\ImageCollection $imageCollection, int $limit = 5, int $offset = 0, $sort = 'date_taken', $order = 'DESC')
    {
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
                    media.status = 'pending'
                  ORDER BY image.{$sort} {$order}
                  LIMIT {$offset}, {$limit}";

        $statement = $this->conn->prepare($query);
        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);
        
        $imageCollection->setCount($this->conn->query('SELECT FOUND_ROWS()')->fetchColumn());

        foreach ($results as $key => $entry) {
            $imageCollection->addEntityFromParams($entry);
        }
    }
}
