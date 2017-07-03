<?php

namespace CommunityVoices\Model\Mapper;

use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity as Entity;

class User extends DataMapper
{
    protected $table = 'community-voices_users';

    public function fetch(Entity\User $user)
    {
        if ($user->getId()) {
            $this->fetchById($user);
            return ;
        }

        $this->fetchByEmail($user);
    }

    private function fetchById(Entity\User $user)
    {
        $query = "SELECT    id,
                            email,
                            fname   AS firstName,
                            lname   AS lastName,
                            role,
                    FROM    {$this->table}
                    WHERE   id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->applyValues($user, $result);
        }
    }

    private function fetchByEmail(Entity\User $user)
    {
        $query = "SELECT    id,
                            email,
                            fname   AS firstName,
                            lname   AS lastName,
                            role,
                    FROM    {$this->table}
                    WHERE   email = :email";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':email', $user->getEmail());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->applyValues($user, $result);
        }
    }

    public function save(Entity\User $user)
    {
        if ($user->getId()) {
            $this->update($user);
            return ;
        }

        $this->register($user);
    }

    private function register(Entity\User $user)
    {
        $query = "INSERT INTO   {$this->table}
                                (email, fname, lname, role)
                    VALEUS      (:email, :fname, :lname, :role)";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $user->getId());
        $statement->bindValue(':email', $user->getEmail());
        $statement->bindValue(':fname', $user->getFirstName());
        $statement->bindValue(':lname', $user->getLastName());
        $statement->bindValue(':role', $user->getRole());

        $statement->execute();

        $user->setId($this->conn->lastInsertId());
    }

    private function update(Entity\User $user)
    {
        $query = "UPDATE    {$this->table}
                    SET     email = :email,
                            fname = :fname,
                            lname = :lname,
                            role = :role
                    WHERE   id = :id";

        $statement = $this->conn->prepare($sql);

        $statement->bindValue(':id', $user->getId());
        $statement->bindValue(':email', $user->getEmail());
        $statement->bindValue(':fname', $user->getFirstName());
        $statement->bindValue(':lname', $user->getLastName());
        $statement->bindValue(':role', $user->getRole());

        $statement->execute();
    }

    public function delete(Entity\User $user)
    {
        $query = "DELETE FROM   {$this->table}
                    WHERE       id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());

        $statement->execute();
    }
}
