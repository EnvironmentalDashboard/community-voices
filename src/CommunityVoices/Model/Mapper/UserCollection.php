<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class UserCollection extends DataMapper
{
    public function fetch(Entity\UserCollection $userCollection)
    {
        $this->fetchAll($userCollection);
    }

    // This and LocationCollection->fetchAll are really repetitive, so
    // either DataMapper (the common parent) should be extended or
    // another parent class should be made.
    public function fetchAll(Entity\UserCollection $userCollection)
    {
        $query = "SELECT
                        id                      AS id,
                        email                   AS email,
                        fname                   AS firstName,
                        lname                   AS lastName,
                        CAST(role AS UNSIGNED)  AS role
                    FROM
                        `community-voices_users`";

        foreach ($this->conn->query($query) as $row) {
            $userCollection->addEntityFromParams($row);
        }
    }
}
