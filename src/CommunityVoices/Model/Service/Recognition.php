<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Recognition
{
    private $pdSearch;

    private $pdIdentification;

    private $mapperFactory;

    private $logger;

    public function __construct(
        Palladium\Service\Search $pdSearch,
        Palladium\Service\Identification $pdIdentification,
        Component\MapperFactory $mapperFactory,
        \Monolog\Logger $logger
    ) {
        $this->pdSearch = $pdSearch;
        $this->pdIdentification = $pdIdentification;

        $this->mapperFactory = $mapperFactory;

        $this->logger = $logger;
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

    public function authenticateByCookie($identity)
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
            $this->logger->error($e->getMessage(), ['exception' => $e]);
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
            $pdIdentity = $this->pdSearch->findCookieIdentity(
                $identity->getAccountId(),
                $identity->getSeries()
            );

            $this->pdIdentification->logout($pdIdentity, $identity->getKey());
        } catch (Palladium\Component\Exception $e) {
            //Don't need to do anything if there's an exception
        }
    }

    public function createUserFromRememberedIdentity(Entity\RememberedIdentity $identity)
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
}
