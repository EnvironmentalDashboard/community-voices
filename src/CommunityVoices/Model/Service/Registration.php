<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Responsible for resgistration-related services
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Registration
{
    private $pdRegistration;

    private $mapperFactory;

    public function __construct(
        Palladium\Service\Registration $pdRegistration,
        Component\MapperFactory $mapperFactory
    ) {
        $this->pdRegistration = $pdRegistration;
        $this->mapperFactory = $mapperFactory;
    }

    /**
     * Registers a user in the database
     * @param  string $email
     * @param  string $password
     * @param  string $confirmPassword
     * @param  string $firstName
     * @param  string $lastName
     * @return boolean True indicates success
     */
    public function createUser($email, $password, $confirmPassword, $firstName, $lastName)
    {
        /**
         * Create user entity and set attributes
         */
        $user = new Entity\User;

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setConfirmPassword($confirmPassword);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        $user->setRole($user::ROLE_UNVERIFIED);


        /**
         * Create error observer, set notifier, and pass it to user validator
         */
        $notifier = new Entity\Notifier;

        $notifier->setNotifier('registration');

        $isValid = $user->validateForRegistration($notifier);

        $clientState = $this->mapperFactory->createSessionMapper(Mapper\ApplicationState::class);

        /**
         * Stop the registration process and save the errors to application state
         * if email is invalid. No point in continuing the validation process in
         * making sure no user has this email if the email is invalid anyway
         */
        if (!$isValid && $notifier->hasEntry('email', $user::ERR_EMAIL_INVALID)) {
            $clientState->save($notifier);
            return false;
        }

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);

        if ($userMapper->existingUserWithEmail($user)) {
            $notifier->addEntry('email', $user::ERR_EMAIL_EXISTS);
        }

        /**
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */
        if ($notifier->hasEntries()) {
            $clientState->save($notifier);
            return false;
        }

        /**
         * Register this user; save with the user mapper and with Palladium
         */
        $userMapper->save($user);

        //`createEmailIdentity()` shouldn't throw IdentityConflict exception
        $pdIdentity = $this->pdRegistration->createEmailIdentity($email, $password);
        $this->pdRegistration->bindAccountToIdentity($user->getId(), $pdIdentity);
        $this->pdRegistration->verifyEmailIdentity($pdIdentity);

        return true;
    }
}
