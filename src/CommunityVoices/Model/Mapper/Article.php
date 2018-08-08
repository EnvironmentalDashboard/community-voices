<?php

/**
 * Article mapper.
 *
 * Articles are a type of Media, thus this class extends the Media mapper and
 * mapping logic in this file occasionally references the Media (parent) mapper.
 *
 * Deletions and updates cascade, thus this mapper does not need to implement logic
 * for deleting entries in the table (it may invoke the parent mapper's method,
 * knowing the delete will cascade down).
 */

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class Article extends Media
{
    /**
     * @uses Article::fetchById
     */
    public function fetch(Entity\Media $article)
    {
        return $this->fetchById($article);
    }

    /**
     * Maps a Article entity by the ID assigned on the instance. If no rows match
     * the article's ID, the Article entity's ID is overwritten as null.
     *
     * @param  Media $article Article entity to fetch & map
     */
    private function fetchById(Entity\Media $article)
    {
        $query = "SELECT
                        parent.id                       AS id,
                        parent.added_by                 AS addedBy,
                        parent.date_created             AS dateCreated,
                        CAST(parent.type AS UNSIGNED)   AS type,
                        CAST(parent.status AS UNSIGNED) AS status,
                        child.image_id                  AS image,
                        child.title                     AS title,
                        child.text                      AS text,
                        child.author                    AS author,
                        child.date_recorded             AS dateRecorded
                    FROM
                        `community-voices_media` parent
                    JOIN
                        `community-voices_articles` child
                        ON parent.id = child.media_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $article->getId());

        $statement->execute();

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        $image = new Entity\Image;
        $image->setId($results['image']);
        $results['image'] = $image;

        if ($results) {
            $convertedParams = $this->convertRelations(
                $this->relations,
                $results
            );

            $this->populateEntity($article, array_merge($results, $convertedParams));
        } else {
            $article->setId(null);
        }
    }

    /**
     * Save a Article entity to database by either: updating a current record if
     * an ID exists or creating a new record.
     *
     * @param  Media $article instance to save to database
     */
    public function save(Entity\Media $article)
    {
        if ($article->getId()) {
            $this->update($article);
            return ;
        }

        $this->create($article);
    }

    protected function update(Entity\Media $article)
    {
        /**
         * Update parent row
         */

        parent::update($article);

        /**
         * Update child row
         */

        $query = "UPDATE
                        `community-voices_articles`
                    SET
                        title = :title,
                        text = :text,
                        author = :author,
                        date_recorded = :date_recorded
                    WHERE
                        media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $article->getId());
        $statement->bindValue(':title', $article->getTitle());
        $statement->bindValue(':text', $article->getText());
        $statement->bindValue(':author', $article->getAuthor());
        $statement->bindValue(':date_recorded', date('Y-m-d H:i:s', $article->getDateRecorded()));

        $statement->execute();
    }

    protected function create(Entity\Media $article)
    {
        /**
         * Create parent row
         */
        parent::create($article);

        /**
         * Credit child row
         */

        $query = "INSERT INTO
                        `community-voices_articles`
                        (media_id, image_id, text, author, date_recorded)
                    VALUES
                        (:media_id, :image_id, :text, :author, :date_recorded)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $article->getId());
        $statement->bindValue(':image_id', $article->getImage()->getId());
        $statement->bindValue(':text', $article->getText());
        $statement->bindValue(':author', $article->getAuthor());
        $statement->bindValue(':date_recorded', date('Y-m-d H:i:s', $article->getDateRecorded()));
        $statement->execute();
    }
}
