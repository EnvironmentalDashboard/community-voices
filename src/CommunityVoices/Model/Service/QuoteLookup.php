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
     * Find quotes by addedBy
     *
     * @param user who added the quotes $addedBy
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findByAddedBy(Entity\User $addedBy)
    {
        $quoteCollection = new Entity\QuoteCollection;

        // create mapper
        // map data

        // check whether collection empty, do something

        // other stuff
    }

    /**
     * Lookup quotes by Group (e.g. tag, content category)
     *
     * @param  Entity\Group $group
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findByGroup(Entity\Group $group)
    {
        $quoteCollection = new Entity\QuoteCollection;

        // create mapper
        // map data

        // check whether collection empty, do something

        // other stuff
    }

    /**
     * Find quotes by status (pending, rejected, approved)
     *
     * @param  quote status $status
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findByStatus(int $status)
    {
    }
}
