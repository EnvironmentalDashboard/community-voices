<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Media extends DataMapper
{
    protected static $table = '`community-voices_media`';

    protected $relations = [
        'single' => [
            'addedBy' => [
                'attributes' => [
                    'id' => 'addedBy'
                ]
            ]
        ],

        'many' => [
            'tagCollection' => [
                'attributes' => [
                    'id' => 'tagId'
                ]
            ],

            'organizationCategoryCollection' => [
                'attributes' => [
                    'id' => 'orgCatId'
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
                        tag.id                              AS tagId,
                        org_cat.id                          AS orgCatId
                    FROM
                        " . self::$table . " media

                    LEFT JOIN
                        `community-voices_media-group-map` junction
                        ON junction.media_id = media.id

                    LEFT JOIN
                        `community-voices_groups` tag
                    ON junction.group_id = tag.id AND CAST(tag.type AS UNSIGNED) = 1

                    LEFT JOIN
                        `community-voices_groups` org_cat
                    ON junction.group_id = org_cat.id AND CAST(org_cat.type AS UNSIGNED) = 2

                    WHERE
                        media.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

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
        $query = "UPDATE    " . self::$table . "
                    SET     added_by = :added_by,
                            type = :type,
                            status = :status
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());
        $statement->bindValue(':added_by', $media->getAddedBy()->getId());
        $statement->bindValue(':type', $media->getType());
        $statement->bindValue(':status', $media->getStatus());
        $statement->execute();
    }

    protected function create(Entity\Media $media)
    {
        $query = "INSERT INTO   " . self::$table . "
                                (added_by, date_created, type, status)
                    VALUES      (:added_by, :date_created, :type, :status)";

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
        $query = "DELETE FROM   " . self::$table . "
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $media->getId());

        $statement->execute();
    }
}
