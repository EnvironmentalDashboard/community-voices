<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for article entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ArticleLookup
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
     * Lookup article by id
     *
     * @param  int $articleId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\Article
     */
    public function findById(int $articleId)
    {
        $article = new Entity\Article;
        $article->setId($articleId);

        $articleMapper = $this->mapperFactory->createDataMapper(Mapper\Article::class);
        $articleMapper->fetch($article);

        if (!$article->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($article->getAddedBy());

        $groupCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupCollectionMapper->fetch($article->getTagCollection());

        $Parsedown = new \Parsedown();
        $article->setText($Parsedown->text($article->getText()));

        $this->stateObserver->setSubject('articleLookup');
        $this->stateObserver->addEntry('article', $article);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Grab all the articles
     *
     * @param $creatorIDs IDs of user who added the articles
     *
     * @return CommunityVoices\Model\Entity\ArticleCollection
     */
    public function findAll($creatorIDs=[], $status=[])
    {
        $articleCollection = new Entity\ArticleCollection;
 
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

        $articleCollection->creators = $creatorIDs;
        $articleCollection->status = $status;

        $articleCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ArticleCollection::class);
        $articleCollectionMapper->fetch($articleCollection);

        $Parsedown = new \Parsedown();
        foreach ($articleCollection->getCollection() as $article) {
            $article->setText($Parsedown->text($article->getText()));
        }

        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        $this->stateObserver->setSubject('articleFindAll');
        $this->stateObserver->addEntry('articleCollection', $articleCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Lookup articles by Group (e.g. tag, content category)
     *
     * @param  int $groupID
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\ArticleCollection
     */
    public function findByGroup(int $groupID)
    {
        $articleCollection = new Entity\ArticleCollection;

        // instantiate and map data to new Group entity
        $group = new Entity\Group;
        $group->setId($groupID);

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\Group::class);
        $groupMapper->fetch($group);

        // no valid Group
        if (!$group->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $articleCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ArticleCollection::class);
        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        // stateObserver stuff

        // clientState stuff
    }
}
