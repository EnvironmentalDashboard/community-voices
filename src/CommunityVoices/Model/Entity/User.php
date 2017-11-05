<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\StateObserver;
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

    protected $allowableRole = [
        self::ROLE_GUEST,
        self::ROLE_UNVERIFIED,
        self::ROLE_USER,
        self::ROLE_MANAGER,
        self::ROLE_ADMIN
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
        $input = (int) $id;

        if ($input > 0) {
            $this->id = (int) $input;
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
        if (in_array($role, $this->allowableRole)) {
            $this->role = (int) $role;
        }
    }

    public function getRole()
    {
        return $this->role;
    }

    private function passwordsMatch()
    {
        return $this->password === $this->confirmPassword;
    }

    public function validateForRegistration(StateObserver $notifier)
    {
        $isValid = true;

        if (!is_null($this->id)) {
            throw new IdentityKnown(self::ERR_IDENTITY_KNOWN);
        }

        if (!$this->firstName || empty($this->firstName)) {
            $isValid = false;
            $notifier->addEntry('firstName', self::ERR_FNAME_REQUIRED);
        }

        if (!$this->lastName || empty($this->lastName)) {
            $isValid = false;
            $notifier->addEntry('lastName', self::ERR_LNAME_REQUIRED);
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $isValid = false;
            $notifier->addEntry('email', self::ERR_EMAIL_INVALID);
        }

        if (strlen($this->password) < 5) {
            $isValid = false;
            $notifier->addEntry('password', self::ERR_PASSWORD_TOO_SHORT);
        }

        if (!$this->passwordsMatch()) {
            $isValid = false;
            $notifier->addEntry('password', self::ERR_PASSWORD_MISMATCH);
        }

        return $isValid;
    }

    public function toJson()
    {
        $arr = [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'role' => $this->role
        ];

        return json_encode($arr);
    }

    public function toXml()
    {
        $arr = [
            'id' => $this->id,
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'role' => $this->role
        ];

        $xml = '<user>';

        foreach ($arr as $key => $value) {
            $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
        }

        $xml .= '</user>';

        return $xml;
    }
}
