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

    const ROLE_GUEST_STRING = 'guest';
    const ROLE_UNVERIFIED_STRING = 'unverified';
    const ROLE_USER_STRING = 'user';
    const ROLE_MANAGER_STRING = 'manager';
    const ROLE_ADMIN_STRING = 'administrator';

    const ERR_EMAIL_INVALID = 'Invalid email address format';
    const ERR_FNAME_REQUIRED = 'First name must be at least 1 character';
    const ERR_LNAME_REQUIRED = 'Last name must be at least 1 character';
    const ERR_EMAIL_EXISTS = 'A user with this email already exists';
    const ERR_IDENTITY_KNOWN = 'ID must be null for registration';
    const ERR_PASSWORD_MISMATCH = 'Password and password confirmation do not match';
    const ERR_PASSWORD_TOO_SHORT = 'Password length must exceed 4 characters';

    const ALLOWABLE_ROLE = [
        self::ROLE_GUEST => self::ROLE_GUEST_STRING,
        self::ROLE_UNVERIFIED => self::ROLE_UNVERIFIED_STRING,
        self::ROLE_USER => self::ROLE_USER_STRING,
        self::ROLE_MANAGER => self::ROLE_MANAGER_STRING,
        self::ROLE_ADMIN => self::ROLE_ADMIN_STRING
    ];

    const STRING_TO_ROLE = [
        self::ROLE_GUEST_STRING => self::ROLE_GUEST,
        self::ROLE_UNVERIFIED_STRING => self::ROLE_UNVERIFIED,
        self::ROLE_USER_STRING => self::ROLE_USER,
        self::ROLE_MANAGER_STRING => self::ROLE_MANAGER,
        self::ROLE_ADMIN_STRING => self::ROLE_ADMIN
    ];

    const ALLOWABLE_DATABASE_ROLE = [
        self::ROLE_UNVERIFIED => self::ROLE_UNVERIFIED_STRING,
        self::ROLE_USER => self::ROLE_USER_STRING,
        self::ROLE_MANAGER => self::ROLE_MANAGER_STRING,
        self::ROLE_ADMIN => self::ROLE_ADMIN_STRING
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
        } else if (is_string($id)) {
            $this->id = (int) $id;
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
        if (array_key_exists($role, self::ALLOWABLE_ROLE)) {
            $this->role = (int) $role;
        } else if (is_string($role)) {
            if (array_key_exists($role, self::STRING_TO_ROLE))
                $this->setRole(self::STRING_TO_ROLE[$role]);
        }
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getRoleTitle()
    {
        return $this->role ? self::ALLOWABLE_ROLE[$this->role] : null;
    }

    public function isRoleAtLeast($role)
    {
        return $this->role >= $role;
    }

    private function passwordsMatch()
    {
        return $this->password === $this->confirmPassword;
    }

    public function validateForSave(FlexibleObserver $stateObserver)
    {
        $isValid = true;

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

        return $isValid;
    }

    public function validateForRegistration(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        if (!is_null($this->id)) {
            throw new IdentityKnown(self::ERR_IDENTITY_KNOWN);
        }

        $this->validateForSave($stateObserver);

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
