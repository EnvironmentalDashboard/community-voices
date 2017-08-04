<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class OrganizationCategory extends Group
{
    protected static $table = '`community-voices_organization-categories`';

    public function fetch(Entity\Group $organizationCategory)
    {
        $this->fetchById($organizationCategory);
    }

    private function fetchById(Entity\Group $organizationCategory)
    {
        $query = "SELECT
                        parent.id                               AS id,
                        parent.label                            AS label,
                        CAST(parent.type AS UNSIGNED)           AS type
                    FROM
                        " . parent::$table . " parent
                    JOIN
                        " . self::$table . " child
                        ON parent.id = child.group_id
                    WHERE
                        parent.id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $organizationCategory->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($organizationCategory, $result);
        }
    }

    public function save(Entity\Group $organizationCategory)
    {
        if ($organizationCategory->getId()) {
            $this->update($organizationCategory);
            return ;
        }

        $this->create($organizationCategory);
    }

    protected function update(Entity\Group $organizationCategory)
    {
        parent::update($organizationCategory);
    }

    protected function create(Entity\Group $organizationCategory)
    {
        parent::create($organizationCategory);

        $query = "INSERT INTO   " . self::$table . "
                                (group_id)
                    VALUES      (:group_id)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':group_id', $organizationCategory->getId());

        $statement->execute();
    }

    public function delete(Entity\Group $organizationCategory)
    {
        parent::delete($organizationCategory); //deletion cascades
    }
}
