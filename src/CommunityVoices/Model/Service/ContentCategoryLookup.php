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

    public function findAll($return = false)
    {
        $contentCategoryCollection = new Entity\ContentCategoryCollection;
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $tagMapper->fetchAllContentCategories($contentCategoryCollection);

        $this->stateObserver->setSubject('contentCategoryLookup');
        $this->stateObserver->addEntry('contentCategory', $contentCategoryCollection);

        if ($return) {
            return $this->stateObserver;
        }

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    public function findById($id)
    {

    }
}
