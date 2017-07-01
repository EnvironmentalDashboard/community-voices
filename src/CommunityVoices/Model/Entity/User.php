<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\ErrorNotifier;
use CommunityVoices\Model\Exception\IdentityKnown;
use Palladium\Contract\HasId;

class User implements HasId
{
    const HASH_ALGO = PASSWORD_BCRYPT;

    const ROLE_GUEST = 0;
    const ROLE_UNVERIFIED = 1;
    const ROLE_USER = 2;
    const ROLE_MANAGER = 3;
    const ROLE_ADMIN = 4;

    const ERR_INVALID_EMAIL = 'Invalid email address';
    const ERR_IDENTITY_KNOWN = 'ID must be null for registration';

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
        $isValid = true;

        if(!is_null($this->id)) {
            throw new IdentityKnown(self::ERR_IDENTITY_KNOWN);
        }

        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $isValid = false;
            $notifier->addError('email', self::ERR_INVALID_EMAIL);
        }

        return $isValid;
    }
}
