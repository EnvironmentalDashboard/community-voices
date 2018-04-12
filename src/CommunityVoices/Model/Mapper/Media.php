<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

// Entity\User, Entity\GroupCollection, Entity\Media

class Media extends DataMapper
{
    protected $relations = [
        'Entity' => [
            'addedBy' => [
                'class' => Entity\User::class,
                'attributes' => [
                    'id' => 'addedBy'
                ]
            ]
        ],
        'Collection' => [
            'tagCollection' => [
                'class' => Entity\GroupCollection::class,
                'attributes' => [
                    'parentId' => 'id'
                ],
                'static' => [
                    'groupType' => Entity\GroupCollection::GROUP_TYPE_TAG,
                    'parentType' => Entity\GroupCollection::PARENT_TYPE_MEDIA,
                ]
            ]
        ]
    ];

    /**
     * @uses Media::fetchById
     */
    public function fetch(Entity\Media $media)
    {
        $this->fetchById($media);
    }

    /**
     * Maps a Media entity by the ID assigned on the instance. If no rows match
     * the media's ID, the Media entity's ID is overwritten as null.
     *
     * @param  Media $media Media entity to fetch & map
     */
    private function fetchById(Entity\Media $media)
    {
        $query = "SELECT
                        media.id                            AS id,
                        media.added_by                      AS addedBy,
                        media.date_created                  AS dateCreated,
                        CAST(media.type AS UNSIGNED)        AS type,
                        CAST(media.status AS UNSIGNED)      AS status
                    FROM
                        `community-voices_media` media
                    WHERE
                        media.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            $convertedParams = $this->convertRelations(
                $this->relations,
                $results
            );

            $this->populateEntity($media, array_merge(
                $results,
                $convertedParams
            ));
        }
    }

    /**
     * save Media to media database
     */
    public function save(Entity\Media $media)
    {
        if ($media->getId()) {
            $this->update($media);
            return;
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

        $media->setId((int) $this->conn->lastInsertId());
        $media->setDateCreated($now);
    }

    /**
     * Removes the database entry associated with the $media ID
     *
     * @param  Media $media Media entity to delete
     */
    public function delete(Entity\Media $media)
    {
        $query = "DELETE FROM
                        `community-voices_media`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();

        $media->setId(null);
    }
}
