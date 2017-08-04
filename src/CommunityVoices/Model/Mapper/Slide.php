<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Slide extends Media
{
    protected static $table = '`community-voices_slides`';

    protected $relations = [
        'single' => [
            'addedBy' => [
                'attributes' => [
                    'id' => 'addedBy'
                ]
            ],
            'contentCategory' => [
                'attributes' => [
                    'id' => 'contentCategoryId'
                ]
            ],
            'image' => [
                'attributes' => [
                    'id' => 'imageId'
                ]
            ],
            'quote' => [
                'attributes' => [
                    'id' => 'quoteId'
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
                    'id' => 'organizationCategoryId'
                ]
            ]
        ]
    ];

    public function fetch(Entity\Media $slide)
    {
        $this->fetchById($slide);
    }

    private function fetchById(Entity\Media $slide)
    {
        $query = "SELECT
                        parent.id                           AS id,
                        parent.added_by                     AS addedBy,
                        parent.date_created                 AS dateCreated,
                        CAST(parent.type AS UNSIGNED)       AS type,
                        CAST(parent.status AS UNSIGNED)     AS status,
                        child.content_category_id           AS contentCategoryId,
                        child.image_id                      AS imageId,
                        child.quote_id                      AS quoteId,
                        child.probability                   AS probability,
                        child.decay_percent                 AS decayPercent,
                        child.decay_start                   AS decayStart,
                        child.decay_end                     AS decayEnd,
                        tag.id                              AS tagId,
                        org_cat.id                          As organizationCategoryId
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

                    LEFT JOIN
                        `community-voices_groups` org_cat
                        ON junction.group_id = org_cat.id AND CAST(org_cat.type AS UNSIGNED) = 2

                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $slide->getId());

        $statement->execute();

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        if ($results) {
            $relations = array_merge_recursive($this->relations, $slide->getRelations());

            $entities = $this->convertSingleRelationsToEntities(
                $relations['single'],
                $results[0]
            );

            $collections = $this->convertManyRelationsToEntityCollections(
                $relations['many'],
                $results
            );

            $this->populateEntity($slide, array_merge(
                $results[0],
                $entities,
                $collections
            ));
        }
    }

    public function save(Entity\Media $slide)
    {
        if ($slide->getId()) {
            $this->update($slide);
            return ;
        }

        $this->create($slide);
    }

    protected function update(Entity\Media $slide)
    {
        parent::update($slide);

        $query = "UPDATE    " . self::$table . "
                    SET     content_category_id = :content_category_id
                            image_id = :image_id,
                            quote_id = :quote_id,
                            probability = :probability,
                            decay_percent = :decay_percent,
                            decay_start = :decay_start,
                            decay_end = :decay_end
                    WHERE   media_id = :media_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $slide->getId());
        $statement->bindValue(':content_category_id', $slide->getContentCategory()->getId());
        $statement->bindValue(':image_id', $slide->getImage()->getId());
        $statement->bindValue(':quote_id', $slide->getQuote()->getId());
        $statement->bindValue(':probability', $slide->getProbability());
        $statement->bindValue(':decay_percent', $slide->getDecayPercent());
        $statement->bindValue(':decay_start', $slide->getDecayStart());
        $statement->bindValue(':decay_end', $slide->getDecayEnd());

        $statement->execute();
    }

    protected function create(Entity\Media $slide)
    {
        parent::create($slide);

        $query = "INSERT INTO   " . self::$table . "
                                (media_id, content_category_id, image_id, quote_id, probability,
                                    decay_percent, decay_start, decay_end)

                    VALUES      (:media_id, :content_category_id, :image_id, :quote_id, :probability,
                                    :decay_percent, :decay_start, :decay_end)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $slide->getId());
        $statement->bindValue(':content_category_id', $slide->getContentCategory()->getId());
        $statement->bindValue(':image_id', $slide->getImage()->getId());
        $statement->bindValue(':quote_id', $slide->getQuote()->getId());
        $statement->bindValue(':probability', $slide->getProbability());
        $statement->bindValue(':decay_percent', $slide->getDecayPercent());
        $statement->bindValue(':decay_start', $slide->getDecayStart());
        $statement->bindValue(':decay_end', $slide->getDecayEnd());

        $statement->execute();
    }

    public function delete(Entity\Media $slide)
    {
        parent::delete($slide); //deletion cascades
    }
}
