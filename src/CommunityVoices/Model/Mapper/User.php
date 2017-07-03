<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class User extends DataMapper
{
    protected $table = 'community-voices_users';

    public function fetch(Entity\User $user)
    {

    }

    public function fetchById(Entity\User $user)
    {
        $query = "SELECT  id,
                        email,
                        fname   AS firstName,
                        lname   AS lastName,
                        role,
                FROM {$this->table}
                WHERE id = :id";

        $statement = $this->conn->prepare($query);

        $statement->execute([
            'id' => $user->getId()
        ]);
    }

    public function fetchByEmail(Entity\User $user)
    {

    }

    public function save(Entity\User $user)
    {

    }

    public function delete(Entity\User $user)
    {

    }
}
