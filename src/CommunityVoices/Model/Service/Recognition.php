<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;

class Recognition
{
    private $pdSearch;

    private $pdIdentification;

    public function __construct(
        Palladium\Service\Search $pdSearch,
        Palladium\Service\Identification $pdIdentification
    ) {
        $this->pdSearch = $pdSearch;
        $this->pdIdentification = $pdIdentification;
    }

    /**
     * Authenticates a user by email and password
     * @param  string $email
     * @param  string $password
     * @return boolean True indicates success
     */
    public function authenticate($email, $password)
    {
        try {
            $pdIdentity = $this->pdSearch->findEmailIdentityByEmailAddress($email);
            $pdCookie = $this->pdIdentification->loginWithPassword($pdIdentity, $password);
        } catch (Palladium\Component\Exception $e) {
            return false; //no need to handle this
        }

        return $pdCookie;
    }

    private function authenticateByCookie($identity)
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

            return false;

        /**
         * Any other exception, just forget the cookie and identify as a guest
         */
        } catch (Palladium\Component\Exception $e) {
            return false;
        }

        return $pdCookie;
    }

    /**
     * Logs out user
     */
    public function logout(Entity\RememberedIdentity $identity)
    {
        try {
            $identity = $this->pdSearch->findCookieIdentity(
                $rememberedIdentity->getAccountId(),
                $rememberedIdentity->getSeries()
            );

            $this->pdIdentification->logout($identity, $rememberedIdentity->getKey());
        } catch (Palladium\Component\Exception $e) {
            //Don't need to do anything if there's an exception
        }
    }
}
