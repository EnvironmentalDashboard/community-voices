<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for tag entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class TagLookup
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

    public function findAll()
    {

        $tagCollection = new Entity\GroupCollection;
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $tagMapper->fetchAllTags($tagCollection);

        $this->stateObserver->setSubject('tagLookup');
        $this->stateObserver->addEntry('tag', $tagCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }
}
