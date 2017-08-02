<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Location extends DataMapper
{
    protected static $table = '`community-voices_locations`';

    public function fetch(Entity\Location $location)
    {
        $this->fetchById($location);
    }

    private function fetchById(Entity\Location $location)
    {
        $query = "SELECT    id,
                            label
                    FROM    " . self::$table . "
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->applyValues($location, $result);
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
        $query = "UPDATE    " . self::$table . "
                    SET     label = :label
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());
        $statement->bindValue(':label', $location->getLabel());
        $statement->execute();
    }

    protected function create(Entity\Location $location)
    {
        $query = "INSERT INTO   " . self::$table . "
                                (label, type)
                    VALUES      (:label, :type)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':label', $location->getLabel());

        $statement->execute();

        $location->setId($this->conn->lastInsertId());
    }

    public function delete(Entity\Location $location)
    {
        $query = "DELETE FROM   " . self::$table . "
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $location->getId());

        $statement->execute();
    }
}
