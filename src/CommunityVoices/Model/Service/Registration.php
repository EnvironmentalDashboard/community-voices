<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Registration
{
    private $pdRegistration;

    private $mapperFactory;

    private $notifier;

    public function __construct(
        Palladium\Service\Registration $pdRegistration,
        Component\MapperFactory $mapperFactory,
        Component\Notifier $notifier
    ) {
        $this->pdRegistration = $pdRegistration;
        $this->mapperFactory = $mapperFactory;
        $this->notifier = $notifier;
    }

    public function createUser($email, $password, $confirmPassword, $firstName, $lastName)
    {
        $user = new Entity\User;

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setConfirmPassword($confirmPassword);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRole($user::ROLE_UNVERIFIED);

        $notifier->setNotifier('registration');

        $isValid = $user->validateForRegistration($notifier);

        if (!$isValid && $notifier->hasError('email', $user::ERR_EMAIL_INVALID)) {
            return ; // @TODO save state to session (through abstraction)
        }

        $userMapper = $this->mapperFactory->create(Mapper\User::class);

        if($userMapper->existingUserWithEmail($user)) {
            $notifier->addError('email', $user::ERR_EMAIL_EXISTS);
            return ; // @TODO save state to session (through abstraction)
        }


        $userMapper->save($user);

        $identity = $this->pdRegistration->createEmailIdentity($email, $password);
        $this->pdRegistration->bindAccountToIdentity($user->getId(), $identity);
        $this->pdRegistration->verifyEmailIdentity($identity);
    }
}
