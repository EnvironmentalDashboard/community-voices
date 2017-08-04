<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class Group extends DataMapper
{
    protected static $table = '`community-voices_groups`';

    public function fetch(Entity\Group $group)
    {
        $this->fetchById($group);
    }

    private function fetchById(Entity\Group $group)
    {
        $query = "SELECT
                        group.id                            AS id,
                        group.label                         AS label,
                        CAST(group.type AS UNSIGNED)        AS type,
                    FROM
                        " . self::$table . " group
                    WHERE
                        group.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $group->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($group, $result);
        }
    }

    public function save(Entity\Group $group)
    {
        if ($group->getId()) {
            $this->update($group);
            return ;
        }

        $this->create($group);
    }

    protected function update(Entity\Group $group)
    {
        $query = "UPDATE    " . self::$table . "
                    SET     label = :label,
                            type = :type
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $group->getId());
        $statement->bindValue(':label', $group->getLabel());
        $statement->bindValue(':type', $group->getType());
        $statement->execute();
    }

    protected function create(Entity\Group $group)
    {
        $query = "INSERT INTO   " . self::$table . "
                                (label, type)
                    VALUES      (:label, :type)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':label', $group->getLabel());
        $statement->bindValue(':type', $group->getType());

        $statement->execute();

        $group->setId($this->conn->lastInsertId());
    }

    public function delete(Entity\Group $group)
    {
        $query = "DELETE FROM   " . self::$table . "
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $group->getId());

        $statement->execute();
    }
}
