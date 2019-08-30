<?php

namespace CommunityVoices\Model\Service;

use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Lookup
{
    private $mapperFactory;
    private $stateObserver;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
    }

    public function getEntity()
    {
        return null;
    }

    public function getCollectionEntity()
    {
        // return new Entity\LocationCollection();
        return null;
    }

    public function getMapper()
    {
        return null;
    }

    public function getCollectionMapper()
    {
        // return Mapper\LocationCollection::class;
        return null;
    }

    public function findAll()
    {
        $collection = $this->getCollectionEntity();
        $collectionMapper = $this->mapperFactory->createDataMapper($this->getCollectionMapper());

        $collectionMapper->fetch($collection);

        $this->stateObserver->setSubject($this);
        $this->stateObserver->addEntry($collection, $collection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }
}
