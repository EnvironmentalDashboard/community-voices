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

        if (!$quoteMapper->fetch($quote)) {
            throw new Exception\IdentityNotFound;
        }

        $this->stateObserver->setSubject('quoteLookup');
        $this->stateObserver->addEntry('quote', $quote);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }
}
