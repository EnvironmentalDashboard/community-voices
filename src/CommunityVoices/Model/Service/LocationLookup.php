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

class LocationLookup extends Lookup
{
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        parent::__construct($mapperFactory, $stateObserver);
    }

    public function getCollectionEntity()
    {
        return new Entity\LocationCollection;
    }

    public function getCollectionMapper()
    {
        return Mapper\LocationCollection::class;
    }

    /**
     * TODO: remove having two findAll by adjusting where this findAll is used
     * this is the misimplementation, as the standard implementation is now stored
     * in Lookup
     */
    public function findAll2($stateObserver, $return = false)
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
