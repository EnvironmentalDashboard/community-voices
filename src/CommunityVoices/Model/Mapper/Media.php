<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Media extends DataMapper
{
    protected $relations = [
        'single' => [
            'addedBy' => [
                'class' => Entity\User::class,
                'attributes' => [
                    'id' => 'addedBy'
                ]
            ]
        ],

        'multiple' => [
            'tagCollection' => [
                'class' => Entity\GroupCollection::class,
                'attributes' => [
                    'id' => 'tagId'
                ]
            ]
        ]
    ];

    public function fetch(Entity\Media $media)
    {
        $this->fetchById($media);
    }

    private function fetchById(Entity\Media $media)
    {
        $query = "SELECT
                        media.id                            AS id,
                        media.added_by                      AS addedBy,
                        media.date_created                  AS dateCreated,
                        CAST(media.type AS UNSIGNED)        AS type,
                        CAST(media.status AS UNSIGNED)      AS status,
                        tag.id                              AS tagId
                    FROM
                        `community-voices_media` media
                    LEFT JOIN
                        `community-voices_media-group-map` junction
                        ON junction.media_id = media.id
                    LEFT JOIN
                        `community-voices_groups` tag
                        ON junction.group_id = tag.id AND CAST(tag.type AS UNSIGNED) = 1
                    WHERE
                        media.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            $entities = $this->makeSingleCardinalityRelations(
                $this->relations['single'],
                $results[0]
            );

            $collections = $this->makeMultipleCardinalityRelations(
                $this->relations['multiple'],
                $results
            );

            $this->populateEntity($media, array_merge(
                $results[0],
                $entities,
                $collections
            ));
        }
    }

    public function save(Entity\Media $media)
    {
        if ($media->getId()) {
            $this->update($media);
            return ;
        }

        $this->create($media);
    }

    protected function update(Entity\Media $media)
    {
        $query = "UPDATE
                        `community-voices_media`
                    SET
                        added_by = :added_by,
                        type = :type,
                        status = :status
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());
        $statement->bindValue(':added_by', $media->getAddedBy()->getId());
        $statement->bindValue(':type', $media->getType());
        $statement->bindValue(':status', $media->getStatus());
        $statement->execute();
    }

    protected function create(Entity\Media $media)
    {
        $query = "INSERT INTO
                        `community-voices_media`
                        (added_by, date_created, type, status)
                    VALUES
                        (:added_by, :date_created, :type, :status)";

        $statement = $this->conn->prepare($query);

        $now = time();

        $statement->bindValue(':added_by', $media->getAddedBy()->getId());
        $statement->bindValue(':date_created', $now);
        $statement->bindValue(':type', $media->getType());
        $statement->bindValue(':status', $media->getStatus());

        $statement->execute();

        $media->setId($this->conn->lastInsertId());
        $media->setDateCreated($now);
    }

    public function delete(Entity\Media $media)
    {
        $query = "DELETE FROM
                        `community-voices_media`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();
    }
}
