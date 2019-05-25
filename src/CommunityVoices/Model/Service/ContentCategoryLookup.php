<?php

namespace CommunityVoices\Model\Service;

use Palladium;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ContentCategoryLookup
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

    public function findAll()
    {
        $contentCategoryCollection = new Entity\ContentCategoryCollection;
        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupMapper->fetchAllContentCategories($contentCategoryCollection);

        $this->stateObserver->setSubject('contentCategoryLookup');
        $this->stateObserver->addEntry('contentCategory', $contentCategoryCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    public function findById($id)
    {

    }
}
