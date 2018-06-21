<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

// Entity\User, Entity\ContentCategory, Entity\Image, Entity\Quote, Entity\GroupCollection, Entity\Media

class Slide extends Media
{
    protected $relations = [
        'Entity' => [
            'addedBy' => [
                'class' => Entity\User::class,
                'attributes' => [
                    'id' => 'addedBy'
                ]
            ],
            'contentCategory' => [
                'class' => Entity\ContentCategory::class,
                'attributes' => [
                    'id' => 'contentCategoryId'
                ]
            ],
            'image' => [
                'class' => Entity\Image::class,
                'attributes' => [
                    'id' => 'imageId'
                ]
            ],
            'quote' => [
                'class' => Entity\Quote::class,
                'attributes' => [
                    'id' => 'quoteId'
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
                    'parentType' => Entity\GroupCollection::PARENT_TYPE_MEDIA
                ]
            ],
            'organizationCategoryCollection' => [
                'class' => Entity\GroupCollection::class,
                'attributes' => [
                    'parentId' => 'id'
                ],
                'static' => [
                    'groupType' => Entity\GroupCollection::GROUP_TYPE_ORG_CAT,
                    'parentType' => Entity\GroupCollection::PARENT_TYPE_MEDIA
                ]
            ]
        ]
    ];

    /**
     * @uses Slide::fetchById
     */
    public function fetch(Entity\Media $slide)
    {
        $this->fetchById($slide);
    }

    /**
     * Fetches a Slide entity by the ID assigned on the instance. If the
     * instance ID isn't found, the ID is overwriten as null.
     *
     * @param Media $slide Slide entity to fetch and map
     */
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
                        child.decay_end                     AS decayEnd
                    FROM
                        `community-voices_media` parent
                    JOIN
                        `community-voices_slides` child
                        ON parent.id = child.media_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $slide->getId());

        $statement->execute();

        $results = $statement->fetch(PDO::FETCH_ASSOC);

        if ($results) {
            $convertedParams = $this->convertRelations($this->relations, $results);

            $this->populateEntity($slide, array_merge($results, $convertedParams));
        } else {
            $slide->setId(null);
        }
    }

    /**
     * Save a Slide entity to database by either: updating a current record if
     * an ID exists or creating a new record.
     *
     * @param  Media $slide instance to save to database
     */
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

        $query = "UPDATE
                        `community-voices_slides`
                    SET
                        content_category_id = :content_category_id
                        image_id = :image_id,
                        quote_id = :quote_id,
                        probability = :probability,
                        decay_percent = :decay_percent,
                        decay_start = :decay_start,
                        decay_end = :decay_end
                    WHERE
                        media_id = :media_id";

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

    /**
     * Creates a slide in database
     *
     * @param  Media $slide to save
     */
    protected function create(Entity\Media $slide)
    {
        parent::create($slide);

        $query = "INSERT INTO
                        `community-voices_slides`
                        (media_id, content_category_id, image_id, quote_id, probability,
                            decay_percent, decay_start, decay_end)
                    VALUES
                        (:media_id, :content_category_id, :image_id, :quote_id, :probability,
                            :decay_percent, :decay_start, :decay_end)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':media_id', $slide->getId());
        $statement->bindValue(':content_category_id', $slide->getContentCategory()->getId());
        $statement->bindValue(':image_id', $slide->getImage()->getId());
        $statement->bindValue(':quote_id', $slide->getQuote()->getId());
        $statement->bindValue(':probability', $slide->getProbability());
        $statement->bindValue(':decay_percent', $slide->getDecayPercent());
        $statement->bindValue(':decay_start', date('Y-m-d', $slide->getDecayStart()));
        $statement->bindValue(':decay_end', date('Y-m-d', $slide->getDecayEnd()));

        $statement->execute();
    }

    public function delete(Entity\Media $slide)
    {
        parent::delete($slide); //deletion cascades
    }
}
