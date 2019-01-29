<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use InvalidArgumentException;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class ArticleCollection extends DataMapper
{
    public function authors(\stdClass $container)
    {
        $authors = [];
        foreach ($this->conn->query('SELECT DISTINCT author FROM `community-voices_articles` WHERE author != "" ORDER BY author ASC') as $row) {
            $obj = new \stdClass();
            $obj->author = htmlspecialchars(htmlspecialchars($row['author']));
            $authors[] = $obj;
        }
        $container->authorCollection = $authors;
    }

    public function fetch(Entity\ArticleCollection $articleCollection, int $limit, int $offset, $order_str, $search, $tags, $authors)
    {
        switch ($order_str) {
            case 'date_recorded_asc':
                $sort = 'date_recorded';
                $order = 'ASC';
                break;
            case 'date_recorded_desc':
                $sort = 'date_recorded';
                $order = 'DESC';
                break;
            case 'author_desc':
                $sort = 'author';
                $order = 'DESC';
                break;
            default:
                $sort = 'date_recorded';
                $order = 'DESC';
                break;
        }
        $this->fetchAll($articleCollection, $limit, $offset, $search, $tags, $authors, $sort, $order);
    }

    private function fetchAll(Entity\ArticleCollection $articleCollection, int $limit, int $offset, $search, $tags, $authors, $sort = 'date_recorded', $order = 'DESC')
    {
        $params = [];
        if ($search == '') {
            $search_query = '';
        } else {
            $search_query = 'AND (text LIKE ? OR author LIKE ? OR title LIKE ?)';
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
            $params[] = "%{$search}%";
        }
        if ($tags == null) {
            $tag_query = '';
        } else {
            $tag_query = 'AND id IN (SELECT media_id FROM `community-voices_media-group-map` WHERE group_id IN ('.implode(',', array_map('intval', $tags)).'))';
        }
        if ($authors == null) {
            $author_query = '';
        } else {
            $author_query = 'AND author IN ('.rtrim(str_repeat('?,', count($authors)), ',').')';
            foreach ($authors as $param) {
                $params[] = $param;
            }
        }

        $query = " 	SELECT SQL_CALC_FOUND_ROWS
						media.id 						AS id,
						media.added_by 					AS addedBy,
						media.date_created 				AS dateCreated,
                        CAST(media.type AS UNSIGNED)    AS type,
                        CAST(media.status AS UNSIGNED)  AS status,
                        article.image_id                AS image,
                        article.title                   AS title,
                        article.text                    AS text,
                        article.author                  AS author,
                        article.date_recorded           AS dateRecorded
					FROM
						`community-voices_media` media
					INNER JOIN
						`community-voices_articles` article
						ON media.id = article.media_id
		          	WHERE 1 {$search_query} {$tag_query} {$author_query}
		         "
                 . $this->query_prep($articleCollection->status, "media.status")
                 . $this->query_prep($articleCollection->creators, "media.added_by")
                 . " ORDER BY article.{$sort} {$order}"
                 . " LIMIT {$offset}, {$limit}";

        $statement = $this->conn->prepare($query);

        $statement->execute($params);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($results as $key => $entry) {
            $image = new Entity\Image;
            $image->setId($entry['image']);
            $entry['image'] = $image;
            $articleCollection->addEntityFromParams($entry);
        }
    }
}
