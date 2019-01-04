<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for locations
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class LocationLookup
{
    private $mapperFactory;

    private $stateObserver;

    /**
     * @param ComponentMapperFactory $mapperFactory Factory for creating mappers
     */
    public function __construct(
        Component\MapperFactory $mapperFactory//,
        // Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        // $this->stateObserver = $stateObserver;
    }

    /**
     * TODO:
     *
     * Need new collection mapper (LocationCollection)
     * Necessary API, Website controller/views
     * Fix/investigate the rest of the service
     * Doc bloc
     */
    public function findAll($stateObserver, $return = false)
    {
        $locCollection = new Entity\LocationCollection;
        $locMapper = $this->mapperFactory->createDataMapper(Mapper\Location::class);
        $locMapper->fetchAll($locCollection);

        $stateObserver->setSubject('locLookup');
        $stateObserver->addEntry('loc', $locCollection);

        if ($return) {
            return $stateObserver;
        }
        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }

    public function findAll2()
    {
        $locationCollection = new Entity\LocationCollection;
        $locationCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\LocationCollection::class);

        $locationCollectionMapper->fetch($locationCollection);

        $this->stateObserver->setSubject($this);
        $this->stateObserver->addEntry('locationCollection', $locationCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }

    public function locationsFor($slideId, $stateObserver, $return = false)
    {
        $locMapper = $this->mapperFactory->createDataMapper(Mapper\Location::class);
        $locs = $locMapper->locationsFor($slideId);

        $stateObserver->setSubject('locLookup');
        $stateObserver->addEntry('selectedLoc', $locs);

        if ($return) {
            return $stateObserver;
        }
        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }
}
