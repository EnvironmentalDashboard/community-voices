<?php

namespace CommunityVoices\Model\Mapper;

use PDO;
use CommunityVoices\Model\Component\DataMapper;
use CommunityVoices\Model\Entity;

class User extends DataMapper
{
    /**
    * Maps a user entity by either the ID or the email assigned on the instance.
    * If an ID is assigned to the object, it is used to fetch info instead of
    * the email.
    * @uses User::fetchById
    * @uses User::fetchByEmail
    */
    public function fetch(Entity\User $user)
    {
        if ($user->getId()) {
            $this->fetchById($user);
            return ;
        }

        $this->fetchByEmail($user);
    }

    /**
     * Maps a User entity by the ID assigned on the instance. If no rows match
     * the user's ID, the User entity's ID is overwritten as null.
     *
     * @param  User $user User entity to fetch & map
     */
    private function fetchById(Entity\User $user)
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
        } else {
            $user->setId(null);
        }
    }

    /**
     * Maps a User entity by the email assigned on the instance. If no rows match
     * the user's email, the User entity's ID is set to null.
     *
     * @param  User $user User entity to fetch & map
     */
    private function fetchByEmail(Entity\User $user)
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
        } else {
            $user->setId(null);
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

    private function update(Entity\User $user)
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

    /**
     * Removes the database entry associated with the $user ID
     *
     * @param User $user User entity to delete
     */
    public function delete(Entity\User $user)
    {
        $query = "DELETE FROM
                        `community-voices_users`
                    WHERE
                        id = :id";

        $statement = $this->conn->prepare($query);

        $statement->bindValue(':id', $user->getId());

        $statement->execute();
    }

    public function existingUserWithEmail(Entity\User $user)
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
