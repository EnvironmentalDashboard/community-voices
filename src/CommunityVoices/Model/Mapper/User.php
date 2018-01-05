<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity\User;

class User extends DataMapper
{
    public function fetch(User $user)
    {
        if ($user->getId()) {
            $this->fetchById($user);
            return ;
        }

        $this->fetchByEmail($user);
    }

    private function fetchById(User $user)
    {
        $query = "SELECT
                        email                   AS email,
                        fname                   AS firstName,
                        lname                   AS lastName,
                        CAST(role AS UNSIGNED)  AS role
                    FROM
                        `community-voices_users`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($user, $result);
        }
    }

    private function fetchByEmail(User $user)
    {
        $query = "SELECT
                        id                      AS id
                        email                   AS email,
                        fname                   AS firstName,
                        lname                   AS lastName,
                        CAST(role AS UNSIGNED)  AS role
                    FROM
                        `community-voices_users`
                    WHERE
                        email = :email";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':email', $user->getEmail());

        $statement->execute();

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $this->populateEntity($user, $result);
        }
    }

    public function save(User $user)
    {
        if ($user->getId()) {
            $this->update($user);
            return ;
        }

        $this->register($user);
    }

    private function register(User $user)
    {
        $query = "INSERT INTO
                        `community-voices_users`
                        (email, fname, lname, role)
                    VALUES
                        (:email, :fname, :lname, :role)";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':email', $user->getEmail());
        $statement->bindValue(':fname', $user->getFirstName());
        $statement->bindValue(':lname', $user->getLastName());
        $statement->bindValue(':role', $user->getRole());

        $statement->execute();

        $user->setId($this->conn->lastInsertId());
    }

    private function update(User $user)
    {
        $query = "UPDATE
                        `community-voices_users`
                    SET
                        email = :email,
                        fname = :fname,
                        lname = :lname,
                        role = :role
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());
        $statement->bindValue(':email', $user->getEmail());
        $statement->bindValue(':fname', $user->getFirstName());
        $statement->bindValue(':lname', $user->getLastName());
        $statement->bindValue(':role', $user->getRole());

        $statement->execute();
    }

    public function delete(User $user)
    {
        $query = "DELETE FROM
                        `community-voices_users`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());

        $statement->execute();
    }

    public function existingUserWithEmail(User $user)
    {
        $query = "SELECT 1 FROM
                        `community-voices_users`
                    WHERE
                        email = :email";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':email', $user->getEmail());

        $statement->execute();

        return !empty($statement->fetch(PDO::FETCH_ASSOC));
    }
}
