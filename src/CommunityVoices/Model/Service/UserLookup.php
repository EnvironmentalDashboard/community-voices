<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for user entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class UserLookup extends Lookup
{
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        parent::__construct($mapperFactory, $stateObserver);
    }

    public function getCollectionEntity()
    {
        return new Entity\UserCollection;
    }

    public function getCollectionMapper()
    {
        return Mapper\UserCollection::class;
    }

    /**
     * Lookup user by id
     *
     * @param  int $userId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\User
     */
    public function findById(int $userId)
    {
        $user = new Entity\User;
        $user->setId($userId);

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($user);

        if (!$user->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $this->stateObserver->setSubject('userLookup');
        $this->stateObserver->addEntry('user', $user);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }
}
