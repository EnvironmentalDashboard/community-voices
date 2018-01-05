<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity\Group;

class Group extends DataMapper
{
    public function fetch(Group $group)
    {
        $this->fetchById($group);
    }

    private function fetchById(Group $group)
    {
        $query = "SELECT
                        group.id                            AS id,
                        group.label                         AS label,
                        CAST(group.type AS UNSIGNED)        AS type,
                    FROM
                        `community-voices_groups` group
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

    public function save(Group $group)
    {
        if ($group->getId()) {
            $this->update($group);
            return ;
        }

        $this->create($group);
    }

    protected function update(Group $group)
    {
        $query = "UPDATE    `community-voices_groups`
                    SET     label = :label,
                            type = :type
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $group->getId());
        $statement->bindValue(':label', $group->getLabel());
        $statement->bindValue(':type', $group->getType());
        $statement->execute();
    }

    protected function create(Group $group)
    {
        $query = "INSERT INTO   `community-voices_groups`
                                (label, type)
                    VALUES      (:label, :type)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':label', $group->getLabel());
        $statement->bindValue(':type', $group->getType());

        $statement->execute();

        $group->setId($this->conn->lastInsertId());
    }

    public function delete(Group $group)
    {
        $query = "DELETE FROM   `community-voices_groups`
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $group->getId());

        $statement->execute();
    }
}
