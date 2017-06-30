<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\ErrorNotifier;

class User
{
    const HASH_ALGO = PASSWORD_BCRYPT;

    const ROLE_GUEST = 0;
    const ROLE_UNVERIFIED = 1;
    const ROLE_USER = 2;
    const ROLE_MANAGER = 3;
    const ROLE_ADMIN = 4;

    private $id;

    private $email;

    private $firstName;
    private $lastName;

    private $hash;

    private $role;

    public function setId($id)
    {
        $this->id = (int) $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setEmail($email)
    {
        $this->email = (string) $email;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setFirstName($firstName)
    {
        $this->firstName = (string) $firstName;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function setLastName($lastName)
    {
        $this->lastName = (string) $lastName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function isValidForRegistration(ErrorNotifier $notifier)
    {
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $notifier->addError('email', 'Invalid email address');
        }

        if(!is_null($this->id)) {
            $notifier->addError('id', 'ID must be null');
        }
        
    }
}
