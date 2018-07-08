<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for slide entities.
 */

 use Palladium;
 use CommunityVoices\Model\Entity;
 use CommunityVoices\Model\Component;
 use CommunityVoices\Model\Mapper;
 use CommunityVoices\Model\Exception;

class SlideLookup
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
     * Grab all the slides
     *
     * @return CommunityVoices\Model\Entity\SlideCollection
     */
    public function findAll(int $page, int $limit, int $offset, array $contentCategories = [])
    {
        $slideCollection = new Entity\SlideCollection;
        $slideCollection->setPage($page);
        $slideCollection->setLimit($limit);

        $slideCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\SlideCollection::class);
        $slideCollectionMapper->fetch($slideCollection, $limit, $offset, $contentCategories);

        $this->stateObserver->setSubject('slideFindAll');
        $this->stateObserver->addEntry('slideCollection', $slideCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Lookup slide by id
     *
     * @param  int $slideId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\Slide
     */
    public function findById(int $slideId)
    {
        $slide = new Entity\Slide;
        $slide->setId($slideId);

        $slideMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);
        $slideMapper->fetch($slide);

        if (!$slide->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($slide->getAddedBy());

        $groupCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupCollectionMapper->fetch($slide->getTagCollection());

        $this->stateObserver->setSubject('slideLookup');
        $this->stateObserver->addEntry('slide', $slide);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Lookup group by id
     *
     * @param  int $groupId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\SlideCollection
     */
    public function findByGroup(int $groupId)
    {
        // @TODO
    }
}
