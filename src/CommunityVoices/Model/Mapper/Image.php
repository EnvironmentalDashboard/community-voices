<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Image extends Media
{
    protected static $table = '`community-voices_images`';

    public function fetch(Entity\Media $image)
    {
        $this->fetchById($image);
    }

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
                        child.organization              AS organization,
                        tag.id                          AS tagId
                    FROM
                        " . parent::$table . " parent
                    JOIN
                        " . self::$table . " child
                        ON parent.id = child.media_id

                    LEFT JOIN
                        `community-voices_media-group-map` junction
                        ON junction.media_id = parent.id

                    LEFT JOIN
                        `community-voices_groups` tag
                        ON junction.group_id = tag.id AND CAST(tag.type AS UNSIGNED) = 1
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $image->getId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            $relations = array_merge_recursive($this->relations, $media->getRelations());

            $entities = $this->convertSingleRelationsToEntities(
                $relations['single'],
                $results[0]
            );

            $collections = $this->convertManyRelationsToEntityCollections(
                $relations['many'],
                $results
            );

            $this->populateEntity($media, array_merge(
                $results[0],
                $entities,
                $collections
            ));
        }
    }

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

        $query = "UPDATE    " . self::$table . "
                    SET     filename = :filename,
                            generated_tags = :generated_tags,
                            title = :title,
                            description = :description,
                            date_taken = :date_taken,
                            photographer = :photographer,
                            organization = :organization
                    WHERE   media_id = :media_id";

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

        $query = "INSERT INTO   " . self::$table . "
                                (media_id, filename, generated_tags, title, description,
                                    date_taken, photographer, organization)

                    VALUES      (:media_id, :filename, :generated_tags, :title, :description,
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

    public function delete(Entity\Media $image)
    {
        parent::delete($image); //deletion cascades
    }
}
