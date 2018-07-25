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

        $tags = new Entity\GroupCollection;
        $tags->forGroupType(1);
        $tags->forParent($quote);
        $quote->setTagCollection($tags);

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
     * Grab all the quotes
     *
     * @param $creatorIDs IDs of user who added the quotes
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findAll(int $page, int $limit, int $offset, string $order, int $only_unused = 0, $search = '', $tags = null, $attributions = null, $creatorIDs=[], $status=[])
    {
        $quoteCollection = new Entity\QuoteCollection;
        $quoteCollection->setPage($page);
        $quoteCollection->setLimit($limit);
        $quoteCollectionAttributions = new \stdClass();
 
        $valid_creatorIDs = [];

        // Validate creator IDs
        if (! empty($creatorIDs)) {
            foreach ($creatorIDs as $userID) {
                // initialize User objects
                $user = new Entity\User;
                $user->setId($userID);

                // map this new User
                $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
                $userMapper->fetch($user);

                // only add valid User 
                if ($user->getId()) {
                    $valid_creatorIDs[] = $userID;
                }
            }
        }

        //var_dump($valid_creatorIDs);

        $quoteCollection->creators = $creatorIDs;
        $quoteCollection->status = $status;

        $quoteCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
        $quoteCollectionMapper->fetch($quoteCollection, $order, $only_unused, $search, $tags, $attributions, $limit, $offset);
        $quoteCollectionMapper->attributions($quoteCollectionAttributions);

        $tagLookup = new TagLookup($this->mapperFactory, $this->stateObserver);
        $tagLookup->findAll();

        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        $this->stateObserver->setSubject('quoteFindAll');
        $this->stateObserver->addEntry('quoteCollection', $quoteCollection);
        $this->stateObserver->addEntry('quoteCollectionAttributions', $quoteCollectionAttributions);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    public function attributions($stateObserver, $return = false) {
        $attributionCollection = new \stdClass;
        $attributionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
        $attributionMapper->attributions($attributionCollection);
        $stateObserver->setSubject('quoteLookup');
        $stateObserver->addEntry('attribution', $attributionCollection);
        if ($return) {
            return $stateObserver;
        }
        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }

    /**
     * Lookup quotes by Group (e.g. tag, content category)
     *
     * @param  int $groupID
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\QuoteCollection
     */
    public function findByGroup(int $groupID)
    {
        $quoteCollection = new Entity\QuoteCollection;

        // instantiate and map data to new Group entity
        $group = new Entity\Group;
        $group->setId($groupID);

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\Group::class);
        $groupMapper->fetch($group);

        // no valid Group
        if (!$group->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $quoteCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\QuoteCollection::class);
        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        // stateObserver stuff

        // clientState stuff
    }


    public function relatedSlide(int $quote_id) {
        $mapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $id = $mapper->relatedSlideId($quote_id);
        return $id;
    }

    public function prevQuote(int $quote_id) {
        $mapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $id = $mapper->prevQuote($quote_id);
        return $id;
    }

    public function nextQuote(int $quote_id) {
        $mapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $id = $mapper->nextQuote($quote_id);
        return $id;
    }
}
