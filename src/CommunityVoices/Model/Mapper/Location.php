<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Location extends DataMapper
{
    public function fetch(Entity\Location $location)
    {
        $this->fetchById($location);
    }

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
            $this->populateEntity($location, $result);
        }
    }

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
