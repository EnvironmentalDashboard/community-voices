<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for quote entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class QuoteLookup
{
    private $mapperFactory;

    private $stateObserver;

    /**
     * @param ComponentMapperFactory $mapperFactory Factory for creating mappers
     */
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
    }

    /**
     * Lookup quote by id
     *
     * @param  int $quoteId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\Quote
     */
    public function findById(int $quoteId)
    {
        $quote = new Entity\Quote;
        $quote->setId($quoteId);

        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $quoteMapper->fetch($quote);

        if (!$quote->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($quote->getAddedBy());

        $groupCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupCollectionMapper->fetch($quote->getTagCollection());

        $this->stateObserver->setSubject('quoteLookup');
        $this->stateObserver->addEntry('quote', $quote);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Grab all the quotes
     *
     * @param $creatorIDs IDs of user who added the quotes
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findAll(int[] $creatorIDs=NULL)
    {
        $quoteCollection = new Entity\QuoteCollection;
 
        // add creators by creator IDs
        foreach ($creatorIDs as $userID){
            // initialize User object
            $quoteCollection->creators[$userID] = new Entity\User;
            $quoteCollection->creators[$userID]->setId($userID);

            // map this new User
            $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
            $userMapper->fetch($user);

            // remove invalid User
            if (!$quoteCollection->creators[$userID]->getId()) {
                unset($quoteCollection->creators[$userID]);
            }
        }

        $quoteCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
        $quoteCollectionMapper->fetch($quoteCollection);

        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        $this->stateObserver->setSubject('quoteFindAll');
        $this->stateObserver->addEntry('quoteCollection', $quoteCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Lookup quotes by Group (e.g. tag, content category)
     *
     * @param  int $groupID
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findByGroup(int $groupID)
    {
        $quoteCollection = new Entity\QuoteCollection;

        // instantiate and map data to new Group entity
        $group = new Entity\Group;
        $group->setId($groupID);

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\Group::class);
        $groupMapper->fetch($group);

        // no valid Group
        if (!$group->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $quoteCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        // stateObserver stuff

        // clientState stuff
    }

    // we are considering doing this in a another way ...
    // /**
    //  * Find quotes by status (pending, rejected, approved)
    //  *
    //  * @param  quote status $status
    //  *
    //  * @throws CommunityVoices\Model\Exception\InvalidStatus
    //  *
    //  * @return CommunityVoices\Model\Entity\QuoteCollection
    //  */
    // public function findByStatus(int $status)
    // {
    //     $quoteCollection = new Entity\QuoteCollection;

    //     // invalid Status
    //     if (!in_array($status, Entity\Media->allowableStatus)){
    //         throw new Exception/InvalidStatus;
    //     }

    //      $quoteCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
    //     // map data
    //     // check whether collection empty, do something


    //     // do we really want to grab all tag collections ???
    //     // if we choose not to, we can make a different toArray() method

    //     // stateObserver stuff

    //     // clientState stuff
    // }
}
