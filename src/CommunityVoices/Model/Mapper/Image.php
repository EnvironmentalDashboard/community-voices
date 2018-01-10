<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class Image extends Media
{

    /**
     * @uses Image::fetchById
     */
    public function fetch(Entity\Media $image)
    {
        $this->fetchById($image);
    }

    /**
     * Fetches an Image entity by the ID assigned on the instance. If the
     * instance ID isn't found, the ID is overwriten as null.
     *
     * @param Media $image Image entity to fetch & map
     */
    private function fetchById(Entity\Media $image)
    {
        $query = "SELECT
                        parent.id                       AS id,
                        parent.added_by                 AS addedBy,
                        parent.date_created             AS dateCreated,
                        CAST(parent.type AS UNSIGNED)   AS type,
                        CAST(parent.status AS UNSIGNED) AS status,
                        child.filename                  AS filename,
                        child.generated_tags            AS generatedTags,
                        child.title                     AS title,
                        child.description               AS description,
                        child.date_taken                AS dateTaken,
                        child.photographer              AS photographer,
                        child.organization              AS organization
                    FROM
                        `community-voices_media` parent
                    JOIN
                        `community-voices_images` child
                        ON parent.id = child.media_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $image->getId());

        $statement->execute();

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            $convertedParams = $this->convertRelations($this->relations, $results);

            $this->populateEntity($image, array_merge($results, $convertedParams));
        } else {
            $image->setId(null);
        }
    }

    /**
     * Save an Image entity to the database by either: updating a current record
     * if the ID exists or creating a new record.
     *
     * @param Media $image Image entity to save to database.
     */
    public function save(Entity\Media $image)
    {
        if ($image->getId()) {
            $this->update($image);
            return ;
        }

        $this->create($image);
    }

    protected function update(Entity\Media $image)
    {
        parent::update($image);

        $query = "UPDATE
                        `community-voices_images`
                    SET
                        filename = :filename,
                        generated_tags = :generated_tags,
                        title = :title,
                        description = :description,
                        date_taken = :date_taken,
                        photographer = :photographer,
                        organization = :organization
                    WHERE
                        media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $image->getId());
        $statement->bindValue(':filename', $image->getFilename());
        $statement->bindValue(':generated_tags', $image->getGeneratedTags());
        $statement->bindValue(':title', $image->getTitle());
        $statement->bindValue(':description', $image->getDescription());
        $statement->bindValue(':date_taken', $image->getDateTaken());
        $statement->bindValue(':photographer', $image->getPhotographer());
        $statement->bindValue(':organization', $image->getOrganization());

        $statement->execute();
    }

    protected function create(Entity\Media $image)
    {
        parent::create($image);

        $query = "INSERT INTO
                        `community-voices_images`
                        (media_id, filename, generated_tags, title, description,
                            date_taken, photographer, organization)

                    VALUES
                        (:media_id, :filename, :generated_tags, :title, :description,
                            :date_taken, :photographer, :organization)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $image->getId());
        $statement->bindValue(':filename', $image->getFilename());
        $statement->bindValue(':generated_tags', $image->getGeneratedTags());
        $statement->bindValue(':title', $image->getTitle());
        $statement->bindValue(':description', $image->getDescription());
        $statement->bindValue(':date_taken', $image->getDateTaken());
        $statement->bindValue(':photographer', $image->getPhotographer());
        $statement->bindValue(':organization', $image->getOrganization());

        $statement->execute();
    }

    /**
     * Invokes parent::delete() method as the Media table's deletion is set to
     * cascade to child rows in the database
     *
     * @param  Media $image to delete
     */
    public function delete(Entity\Media $image)
    {
        parent::delete($image); //deletion cascades
    }
}
