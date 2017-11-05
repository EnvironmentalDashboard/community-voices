<?php

namespace CommunityVoices\App\Website\Component;

use CommunityVoices\Model\Service\Recognition;

use CommunityVoices\Model\Entity;

use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Website\Component\Mapper;

class RecognitionAdapter implements \CommunityVoices\App\Api\Component\Contract\CanIdentify
{
    private $mapperFactory;

    private $recognition;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Recognition $recognition
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->recognition = $recognition;
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
        $cookie = $this->recognition->authenticate($email, $password);

        if (!$cookie) {
            return false; //Authentication failed
        }

        if ($remember) {
            $this->rememberCookie($cookie);
        }

        /**
         * Write user to session
         */
        $this->persistSession($cookie);

        return true;
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
    private function discardCookie()
    {
        $rememberedIdentity = new Entity\RememberedIdentity;
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        if ($cookieMapper->fetch($rememberedIdentity) !== false) {
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
        return $this->recognition->createUserFromRememberedIdentity($rememberedIdentity); //identify by session is easy
    }

    /**
     * Identifies a user through cookies
     */
    private function identifyByCookie($identity)
    {
        $cookie = $this->recognition->authenticateByCookie($identity);

        if (!$cookie) {
            $this->discardCookie($identity);

            return $this->createGuestUser();
        }

        /**
         * Update remember-me cookie and persist identity in session
         */
        $this->rememberCookie($cookie);
        $this->persistSession($cookie);

        return $this->recognition->createUserFromRememberedIdentity($identity);
    }

    private function createGuestUser()
    {
        $user = new Entity\User;

        $user->setRole($user::ROLE_GUEST);

        return $user;
    }

    /**
     * Logs out user
     */
    public function logout()
    {
        $rememberedIdentity = new Entity\RememberedIdentity;

        /**
         * Attept to identify by session
         */
        $sessionMapper = $this->mapperFactory->createSessionMapper(Mapper\Session::class);
        $sessionMapper->fetch($rememberedIdentity);

        if ($rememberedIdentity->getAccountId()) {
            $this->recognition->logout($rememberedIdentity);

            // Log user out by deleting cookie & session states
            $this->discardCookie();
            $this->ceaseSession();
        }
    }
}
