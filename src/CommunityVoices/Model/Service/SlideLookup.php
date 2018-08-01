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
    public function findAll(int $page, int $limit, int $offset, string $order, string $search, $tags, $photographers, $orgs, $attributions, array $contentCategories = [], $stateObserver = false)
    {
        $chained = ($stateObserver instanceof Component\StateObserver);
        if (!$chained) {
            $stateObserver = $this->stateObserver;
        }

        $slideCollection = new Entity\SlideCollection;
        $slideCollection->setPage($page);
        $slideCollection->setLimit($limit);

        $slideCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\SlideCollection::class);
        $slideCollectionMapper->fetch($slideCollection, $limit, $offset, $order, $search, $tags, $photographers, $orgs, $attributions, $contentCategories);

        $stateObserver->setSubject('slideFindAll');
        $stateObserver->addEntry('slideCollection', $slideCollection);

        if ($chained) {
            return $stateObserver;
        }

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
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
    public function findById(int $slideId, $stateObserver = null)
    {
        if ($stateObserver === null) {
            $stateObserver = $this->stateObserver;
        }
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

        $stateObserver->setSubject('slideLookup');
        $stateObserver->addEntry('slide', $slide);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
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
