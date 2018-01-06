<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class Tag extends Group
{
    public function fetch(Entity\Group $tag)
    {
        $this->fetchById($tag);
    }

    private function fetchById(Entity\Group $tag)
    {
        $query = "SELECT
                        parent.id                                   AS id,
                        parent.label                                AS label,
                        CAST(parent.type AS UNSIGNED)               AS type
                    FROM
                        `community-voices_groups` parent
                    JOIN
                        `community-voices_tags` child
                        ON parent.id = child.group_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $tag->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($tag, $result);
        }
    }

    public function save(Entity\Group $tag)
    {
        if ($tag->getId()) {
            $this->update($tag);
            return ;
        }

        $this->create($tag);
    }

    protected function update(Entity\Group $tag)
    {
        parent::update($tag);
    }

    protected function create(Entity\Group $tag)
    {
        parent::create($tag);

        $query = "INSERT INTO
                        `community-voices_tags`
                        (group_id)
                    VALUES
                        (:group_id)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':group_id', $tag->getId());

        $statement->execute();
    }

    public function delete(Entity\Group $tag)
    {
        parent::delete($tag); //deletion cascades
    }
}
