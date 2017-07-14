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
            $this->rememberCookie($pdCookie);
        }

        /**
         * Write user to session
         */

        $this->persistSession($pdIdentity);
    }

    /**
     * Saves a user to cookie
     */
    private function rememberCookie($pdCookie)
    {
        $rememberedIdentity = new Entity\RememberedIdentity;

        $rememberedIdentity->setAccountId($pdCookie->getAccountId());
        $rememberedIdentity->setKey($pdCookie->getKey());
        $rememberedIdentity->setSeries($pdCookie->getSeries());
        $rememberedIdentity->setExpiresOn($pdCookie->getExpiresOn());

        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);
        $cookieMapper->save($rememberedIdentity);
    }

    /**
     * Deletes a user from cookie
     */
    private function discardCookie($forgetPdCookie = false)
    {
        $rememberedIdentity = new Entity\RememberedIdentity;
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        if ($cookieMapper->fetch($rememberedIdentity) !== false) {
            try {
                $identity = $this->pdSearch->findCookieIdentity(
                    $rememberedIdentity->getAccountId(),
                    $rememberedIdentity->getSeries()
                );

                $this->pdIdentification->logout($identity, $rememberedIdentity->getKey());
            } catch (Palladium\Component\Exception $e) {
                //Don't need to do anything if there's an exception
            }

            $cookieMapper->delete($rememberedIdentity);
        }
    }

    /**
     * Persists a user in session
     */
    private function persistSession($pdIdentity)
    {
        $rememberedIdentity = new Entity\RememberedIdentity;

        $rememberedIdentity->setAccountId($pdIdentity->getAccountId());

        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);
        $sessionMapper->save($rememberedIdentity);
    }

    private function ceaseSession()
    {
        $rememberedIdentity = new Entity\RememberedIdentity;
        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);

        if ($sessionMapper->fetch($rememberedIdentity) !== false) {
            $sessionMapper->delete($rememberedIdentity);
        }
    }

    /**
     * Attempts to identify client
     * @return CommunityVoices\Model\Entity\User $user Identified client
     */
    public function identify()
    {
        $rememberedIdentity = new Entity\RememberedIdentity;

        /**
         * Attept to identify by session
         */
        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);
        $sessionMapper->fetch($rememberedIdentity);

        if ($rememberedIdentity->getAccountId()) {
            return $this->identifyBySession($rememberedIdentity);
        }

        /**
         * Attempt to identify by cookie
         */
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);
        $cookieMapper->fetch($rememberedIdentity);

        if ($rememberedIdentity->getAccountId()) {
            return $this->identifyByCookie($rememberedIdentity);
        }

        return $this->createGuestUser();
    }

    /**
     * Identifies a user through the session state
     */
    private function identifyBySession($rememberedIdentity)
    {
        return $this->createUserFromRememberedIdentity($rememberedIdentity); //identify by session is easy
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

        /**
         * Block & delete compromised cookies
         */
        } catch (Palladium\Exception\CompromisedCookie $e) {
            $this->pdIdentification->blockIdentity($pdIdentity);

            $this->discardCookie($identity);

            return $this->createGuestUser();

        /**
         * Any other exception, just forget the cookie and identify as a guest
         */
        } catch (Palladium\Component\Exception $e) {
            $this->discardCookie($identity);

            return $this->createGuestUser();
        }

        /**
         * Update remember-me cookie and persist identity in session
         */
        $this->rememberCookie($pdCookie);
        $this->persistSession($pdIdentity);

        return $this->createUserFromRememberedIdentity($identity);
    }

    private function createGuestUser()
    {
        $user = new Entity\User;

        $user->setRole($user::ROLE_GUEST);

        return $user;
    }

    private function createUserFromRememberedIdentity($identity)
    {
        $user = new Entity\User;

        $user->setId($identity->getAccountId());

        /**
         * Attept to fetch via cache
         */
        $cacheMapper = $this->mapperFactory->createCacheMapper(Mapper\Cache::class);

        if ($cacheMapper->exists($user)) {
            $cacheMapper->fetch($user);

            return $user;
        }

        /**
         * Cache failed; fetch user in database
         */
        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($user);

        /**
         * Save user to cache
         */
        $cacheMapper->save($user);

        return $user;
    }

    /**
     * Logs out user
     */
    public function logout()
    {
        $identity = $this->identify();

        // If the client is a guest, whatever
        if ($identity->getRole() === Entity\User::ROLE_GUEST) {
            return ;
        }

        // Log user out by deleting cookie & session states
        $this->discardCookie();
        $this->ceaseSession();
    }
}
