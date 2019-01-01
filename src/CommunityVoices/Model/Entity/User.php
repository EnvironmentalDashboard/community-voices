<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;
use CommunityVoices\Model\Contract\HasId;
use CommunityVoices\Model\Exception\IdentityKnown;
use Palladium;

class User implements HasId, Palladium\Contract\HasId
{
    const HASH_ALGO = PASSWORD_BCRYPT;

    const ROLE_GUEST = 0;
    const ROLE_UNVERIFIED = 1;
    const ROLE_USER = 2;
    const ROLE_MANAGER = 3;
    const ROLE_ADMIN = 4;

    const ERR_EMAIL_INVALID = 'Invalid email address';
    const ERR_FNAME_REQUIRED = 'First name must be at least 1 character';
    const ERR_LNAME_REQUIRED = 'Last name must be at least 1 character';
    const ERR_EMAIL_EXISTS = 'User with this email already exists';
    const ERR_IDENTITY_KNOWN = 'ID must be null for registration';
    const ERR_PASSWORD_MISMATCH = 'Confirm password must match';
    const ERR_PASSWORD_TOO_SHORT = 'Password length must exceed 4 characters';

    private $allowableRole = [
        self::ROLE_GUEST => 'guest',
        self::ROLE_UNVERIFIED => 'new user',
        self::ROLE_USER => 'user',
        self::ROLE_MANAGER => 'manager',
        self::ROLE_ADMIN => 'administrator'
    ];

    private $id;

    private $email;
    private $password;
    private $confirmPassword;

    private $firstName;
    private $lastName;

    private $role;

    public function setId($id)
    {
        if (is_int($id) || is_null($id)) {
            $this->id = $id;
        }
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

    public function setPassword($password)
    {
        $this->password = (string) $password;
    }

    public function setConfirmPassword($confirmPassword)
    {
        $this->confirmPassword = (string) $confirmPassword;
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
        if (array_key_exists($role, $this->allowableRole)) {
            $this->role = (int) $role;
        }
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getRoleTitle()
    {
        return $this->role ? $this->allowableRole[$this->role] : null
    }

    private function passwordsMatch()
    {
        return $this->password === $this->confirmPassword;
    }

    public function validateForRegistration(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        if (!is_null($this->id)) {
            throw new IdentityKnown(self::ERR_IDENTITY_KNOWN);
        }

        if (!$this->firstName || empty($this->firstName)) {
            $isValid = false;
            $stateObserver->addEntry('firstName', self::ERR_FNAME_REQUIRED);
        }

        if (!$this->lastName || empty($this->lastName)) {
            $isValid = false;
            $stateObserver->addEntry('lastName', self::ERR_LNAME_REQUIRED);
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $isValid = false;
            $stateObserver->addEntry('email', self::ERR_EMAIL_INVALID);
        }

        if (strlen($this->password) < 5) {
            $isValid = false;
            $stateObserver->addEntry('password', self::ERR_PASSWORD_TOO_SHORT);
        }

        if (!$this->passwordsMatch()) {
            $isValid = false;
            $stateObserver->addEntry('password', self::ERR_PASSWORD_MISMATCH);
        }

        return $isValid;
    }

    public function toArray()
    {
        return ['user' => [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'role' => $this->getRoleTitle()
        ]];
    }
}
