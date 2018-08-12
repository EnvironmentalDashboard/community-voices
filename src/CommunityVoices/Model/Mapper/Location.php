<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

// Entity\GroupCollection, Entity\ContentCategoryCollection, Entity\Location

class Location extends DataMapper
{
    /**
     * @todo Add probability
     */
    protected $relations = [
        'Collection' => [
            'organizationCategoryCollection' => [
                'class' => Entity\GroupCollection::class,
                'attributes' => [
                    'parentId' => 'id'
                ],
                'static' => [
                    'groupType' => Entity\GroupCollection::GROUP_TYPE_ORG_CAT,
                    'parentType' => Entity\GroupCollection::PARENT_TYPE_LOCATION,
                ]
            ],
            'contentCategoryCollection' => [
                'class' => Entity\ContentCategoryCollection::class,
                'attributes' => [
                    'parentId' => 'id'
                ],
                'static' => [
                    'groupType' => Entity\GroupCollection::GROUP_TYPE_CONT_CAT,
                    'parentType' => Entity\GroupCollection::PARENT_TYPE_LOCATION,
                ]
            ]
        ]
    ];

    public function fetchAll(Entity\LocationCollection $container)
    {
        $query = "SELECT id, label FROM `community-voices_locations`";
        foreach ($this->conn->query($query) as $row) {
            $loc = new Entity\Location;
            $loc->setId((int) $row['id']);
            $loc->setLabel($row['label']);
            $container->addEntity($loc);
        }
    }

    public function locationsFor($slideId) {
        $query = "SELECT id FROM `community-voices_locations` WHERE id IN (SELECT loc_id FROM `community-voices_media-location-map` WHERE media_id = :slideId)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':slideId', $slideId);
        $stmt->execute();
        return array_column($stmt->fetchAll(PDO::FETCH_ASSOC), 'id');
    }

    public function link($slideId, $screenId) {
        $query = "INSERT INTO `community-voices_media-location-map` (media_id, loc_id) VALUES (?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$slideId, $screenId]);
    }

    /**
     * @uses Location::fetchById
     */
    public function fetch(Entity\Location $location)
    {
        $this->fetchById($location);
    }

    /**
     * Maps a Location entity by the ID assigned on the instance. If no rows
     * match the location's ID, the Location entity's ID is overwritten as null.
     *
     * @param  Location $location Location entity to fetch & map
     */
    private function fetchById(Entity\Location $location)
    {
        $query = "SELECT
                        id                      AS id,
                        label                   AS label
                    FROM
                        `community-voices_locations`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $convertedParams = $this->convertRelations($this->relations, $result);

            $this->populateEntity($location, array_merge($result, $convertedParams));
        } else {
            $location->setId(null);
        }
    }

    /**
     * Save a Location entity to database by either: updating a current record
     * if an ID exists or creating a new record.
     *
     * @param  Location $location instance to save to database
     */
    public function save(Entity\Location $location)
    {
        if ($location->getId()) {
            $this->update($location);
            return ;
        }

        $this->create($location);
    }

    protected function update(Entity\Location $location)
    {
        $query = "UPDATE
                        `community-voices_locations`
                    SET
                        label = :label
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());
        $statement->bindValue(':label', $location->getLabel());
        $statement->execute();
    }

    protected function create(Entity\Location $location)
    {
        $query = "INSERT INTO
                        `community-voices_locations`
                        (label, type)
                    VALUES
                        (:label, :type)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':label', $location->getLabel());

        $statement->execute();

        $location->setId($this->conn->lastInsertId());
    }

    /**
     * Removes the database entry associated with the $location ID
     *
     * @param  Location $location Location entity to delete
     */
    public function delete(Entity\Location $location)
    {
        $query = "DELETE FROM
                        `community-voices_locations`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());

        $statement->execute();
    }
}
