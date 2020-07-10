<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class Tag extends Group
{
    /**
     * @uses Tag::fetchById
     */
    public function fetch(Entity\Group $tag)
    {
        $this->fetchById($tag);
    }

    /**
     * Maps a Tag entity by the ID assigned on the instance. If no rows match
     * the entity's ID, the entity's ID is overwritten as null.
     *
     * @param  Group $tag Tag entity to fetch & map
     */
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

        $statement->bindValue(':id', $tag->getGroupId());
    
        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($tag, $result);
        } else {
            $tag->setGroupId(null);
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

        $statement->bindValue(':group_id', $tag->getGroupId());

        $statement->execute();
    }

    /**
     * Invokes parent::delete() method as the Media table's deletion is set to
     * cascade to child rows in the database
     *
     * @param  Group $tag to delete
     */
    public function delete(Entity\Group $tag)
    {
        parent::delete($tag); //deletion cascades
    }
}
