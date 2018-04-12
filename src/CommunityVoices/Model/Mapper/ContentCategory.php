<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Entity;

class ContentCategory extends Group
{
    /**
     * @uses ContentCategory::fetchById
     */
    public function fetch(Entity\Group $contentCategory)
    {
        $this->fetchById($contentCategory);
    }

    /**
     * Maps a ContentCategory entity by the ID assigned on the instance. If no
     * rows match the entity's ID, the entity's ID is overwritten as null.
     *
     * @param  Group $contentCategory ContentCategory entity to fetch & map
     */
    private function fetchById(Entity\Group $contentCategory)
    {
        $query = "SELECT
                        parent.id                           AS id,
                        parent.label                        AS label,
                        CAST(parent.type AS UNSIGNED)       AS type,
                        child.media_filename                AS mediaFilename
                    FROM
                        `community-voices_groups` parent
                    JOIN
                        `community-voices_content-categories` child
                        ON parent.id = child.group_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $contentCategory->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($contentCategory, $result);
        } else {
            $contentCategory->setId(null);
        }
    }

    /**
     * Save a ContentCategory entity to database by either: updating a current
     * record if an ID exists or creating a new record.
     *
     * @param  Group $contentCategory ContentCategory instance to save to database
     */
    public function save(Entity\Group $contentCategory)
    {
        if ($contentCategory->getId()) {
            $this->update($contentCategory);
            return ;
        }

        $this->create($contentCategory);
    }

    protected function update(Entity\Group $contentCategory)
    {
        parent::update($contentCategory);

        $query = "UPDATE
                        `community-voices_content-categories`
                    SET
                        media_filename = :media_filename
                    WHERE
                        group_id = :group_id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':group_id', $contentCategory->getId());

        $statement->execute();
    }

    protected function create(Entity\Group $contentCategory)
    {
        parent::create($contentCategory);

        $query = "INSERT INTO
                        `community-voices_content-categories`
                        (group_id, media_filename)
                    VALUES
                        (:group_id, :media_filename)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':group_id', $contentCategory->getId());
        $statement->bindValue(':media_filename', $contentCategory->getMediaFilename());

        $statement->execute();
    }

    /**
     * Invokes parent::delete() method as the Media table's deletion is set to
     * cascade to child rows in the database
     *
     * @param  Group $contentCategory to delete
     */
    public function delete(Entity\Group $contentCategory)
    {
        parent::delete($contentCategory); //deletion cascades
    }
}
