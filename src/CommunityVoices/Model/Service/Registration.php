<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Responsible for resgistration-related services
 */

use Palladium;
use Swift_Message;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Registration
{
    private $pdRegistration;
    private $mapperFactory;
    private $stateObserver;
    private $mailer;
    private $dkim;

    public function __construct(
        Emailer $emailService,
        Palladium\Service\Registration $pdRegistration,
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver,
        Emailer $mailer
    ) {
        $this->emailService = $emailService;
        $this->pdRegistration = $pdRegistration;
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
        $this->mailer = $mailer;
    }

    /**
     * Registers a user in the database
     * @param  string $email
     * @param  string $password
     * @param  string $confirmPassword
     * @param  string $firstName
     * @param  string $lastName
     * @param  string $token
     * @return boolean True indicates success
     */
    public function createUser($email, $password, $confirmPassword, $firstName, $lastName, $token)
    {
        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);

        /**
         * Create user entity and set attributes
         */
        $user = new Entity\User;

        $user->setEmail($email);
        $user->setPassword($password);
        $user->setConfirmPassword($confirmPassword);
        $user->setFirstName($firstName);
        $user->setLastName($lastName);
        if ($token !== '') {
            $role = $userMapper->invitedRole($user, $token);
            $user->setRole((int) $role);
        } else {
            $user->setRole($user::ROLE_GUEST);
        }


        /**
         * Create error observer, set the subject, and pass it to user validator
         */
        $this->stateObserver->setSubject('registration');

        $isValid = $user->validateForRegistration($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /**
         * Stop the registration process and save the errors to application state
         * if email is invalid. No point in continuing the validation process in
         * making sure no user has this email if the email is invalid anyway
         */
        if (!$isValid && $this->stateObserver->hasEntry('email', $user::ERR_EMAIL_INVALID)) {
            $clientState->save($this->stateObserver);
            return false;
        }

        if ($userMapper->existingUserWithEmail($user)) {
            $this->stateObserver->addEntry('email', $user::ERR_EMAIL_EXISTS);
        }

        /**
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */
        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            // var_dump($this->stateObserver->getEntriesBySubject('registration')); // NEED TO DISPLAY ERROR TO USER
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

    public function insertToken($email, $role, $token)
    {
        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->insertToken($email, $role, $token);
    }

    public function sendInviteEmail($email, $role, $token)
    {
        /**
         * Gather position title information
         */
        $user = new Entity\User;
        $user->setRole($role);

        $position = $user->getRoleTitle();

        /**
         * Compose message
         */
        $message = new Swift_Message();

        $message->setTo($email);
        $message->setSubject("You're invited to be a {$position} on Community Voices");
        $message->setBody("<p>You have been invited to create a new {$position} account.<a href='https://environmentaldashboard.org/community-voices/register?token={$token}'>Click here</a> to complete the registration process.</p>");

        /**
         * Create DKIM signature
         */

        $this->mailer->send($message);
    }
}
