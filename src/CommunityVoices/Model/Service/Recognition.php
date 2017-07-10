<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Entity;

class Recognition
{
    private $pdSearch;

    private $pdIdentification;

    private $mapperFactory;

    public function __construct(
        Palladium\Service\Search $pdSearch,
        Palladium\Service\Identification $pdIdentification,
        Component\MapperFactory $mapperFactory
    ) {
        $this->pdSearch = $pdSearch;
        $this->pdIdentification = $pdIdentification;
        $this->mapperFactory = $mapperFactory;
    }
    /**
     * Authenticates a user by email and password
     * @param  string $email
     * @param  string $password
     * @param  boolean $remember Whether to set remember-me cookie
     * @return boolean True indicates success
     */
    public function authenticate($email, $password, $remember = false)
    {
        try {
            $pdIdentity = $this->pdSearch->findEmailIdentityByEmailAddress($email);
            $pdCookie = $this->pdIdentification->loginWithPassword($pdIdentity, $password);
        } catch (Palladium\Component\Exception $e) {
            return false; //no need to handle this
        }

        if ($remember) {
            $this->remember($pdIdentity, $pdCookie);
        }

        /**
         * Write user to session
         */

        $this->persist($pdIdentity);
    }

    /**
     * Saves a user to cookie
     */
    private function remember($pdIdentity, $pdCookie)
    {
        $identity = new Entity\RememberedIdentity;

        $identity->setAccountId($pdIdentity->getAccountId());
        $identity->setKey($pdCookie->getKey());
        $identity->setSeries($pdCookie->getSeries());
        $identity->setExpiresOn($pdIdentity->getExpiresOn());

        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);
        $cookieMapper->save($identity);
    }

    /**
     * Persists a user in session
     */
    private function persist($pdIdentity)
    {
        $identity = new Entity\RememberedIdentity;

        $identity->setAccountId($pdIdentity->getAccountId());

        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);
        $sessionMapper->save($identity);
    }

    /**
     * Attempts to identify client
     * @return CommunityVoices\Model\Entity\User $user Identified client
     */
    public function identify()
    {
        $identity = new Entity\RememberedIdentity;

        /**
         * Attept to identify by session
         */
        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);
        $sessionMapper->fetch($identity);

        if ($identity->getAccountId()) {
            return $this->identifyBySession($identity);
        }

        /**
         * Attempt to identify by cookie
         */
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);
        $cookieMapper->fetch($identity);

        if ($identity->getAccountId()) {
            return $this->identifyByCookie($identity);
        }

        return $this->createGuestUser();
    }

    /**
     * Identifies a user through the session state
     */
    private function identifyBySession($identity)
    {
        return $this->createUser($identity); // identify by session is easy
    }

    /**
     * Identifies a user through cookies
     */
    private function identifyByCookie($identity)
    {
        try {
            $pdIdentity = $this->pdSearch->findCookieIdentity(
                $identity->getAccountId(),
                $identity->getSeries()
            );

            $pdCookie = $this->pdIdentification->loginWithCookie($pdIdentity, $identity->getKey());
        } catch (Palladium\Exception\CompromisedCookie $e) {
            $this->pdIdentification->blockIdentity($pdIdentity);

            $this->forgetCookie($identity);

            return $this->createGuestUser();
        } catch (Palladium\Component\Exception $e) {
            $this->forgetCookie($identity);

            return $this->createGuestUser();
        }

        /**
         * Update remember-me cookie and persist identity in session
         */
        $this->remember($pdIdentity, $pdCookie);
        $this->persist($pdIdentity);

        return $this->createUser($identity);
    }

    private function createGuestUser()
    {
        $user = new Entity\User;

        $user->setRole($user::ROLE_GUEST);

        return $user;
    }

    private function createUser($identity)
    {
        $user = new Entity\User;

        $user->setId($identity->getAccountId());

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($user);

        return $user;
    }

    private function forgetCookie($identity)
    {
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $cookieMapper->remove($identity);
    }

    public function logout()
    {

    }
}
