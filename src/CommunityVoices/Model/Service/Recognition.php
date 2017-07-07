<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Recognition
{
    private $pdSearch;

    private $pdIdentification;

    private $mapperFactory;

    public function __construct(
        Palladiun\Service\Search $pdSearch,
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
    public function authenticate($email, $password, $remember)
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
    }

    private function remember($pdIdentity, $pdCookie)
    {
        $identity = new Entity\RememberedIdentity;

        $identity->setAccountId($pdIdentity->getAccountId());
        $identity->setKey($pdCookie->getKey());
        $identity->setSeries($pdCookie->getSeries());
        $identity->setExpireTime($pdIdentity->getExpiresOn());

        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $cookieMapper->save($identity);
    }

    public function identity()
    {
    }

    public function logout()
    {
    }
}
