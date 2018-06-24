<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for image entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ImageLookup
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

    public function printById(int $imageId)
    {
        $image = new Entity\Image;
        $image->setId($imageId);

        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $imageMapper->fetch($image);

        if (!$image->getId()) {
            throw new Exception\IdentityNotFound;
        }
        $fn = $image->getFilename();
        if (!file_exists($fn)) {
            throw new Exception\IdentityNotFound;
        }

        $extension = explode('.', $fn)[1];
        $extension = ($extension === 'jpg') ? 'jpeg' : $extension;
        header('Content-type: image/' . $extension);
        readfile($fn);
        exit;
    }

    /**
     * Lookup image by id
     *
     * @param  int $imageId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\Image
     */
    public function findById(int $imageId)
    {
        $image = new Entity\Image;
        $image->setId($imageId);

        $imageMapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $imageMapper->fetch($image);

        if (!$image->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($image->getAddedBy());

        $groupCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupCollectionMapper->fetch($image->getTagCollection());

        $this->stateObserver->setSubject('imageLookup');
        $this->stateObserver->addEntry('image', $image);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Grab all the images
     *
     * @return CommunityVoices\Model\Entity\ImageCollection
     */
    public function findAll(int $limit = 5, int $offset = 0)
    {
        $imageCollection = new Entity\ImageCollection;

        $imageCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        $imageCollectionMapper->fetch($imageCollection, $limit, $offset);

        // I am uncertain about this
        $this->stateObserver->setSubject('imageFindAll');
        $this->stateObserver->addEntry('imageCollection', $imageCollection);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($this->stateObserver);
    }

    /**
     * Find images by creator
     *
     * @param ID of user who added the images $creator
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\ImageCollection
     */
    public function findByCreator(int $creator)
    {
        $imageCollection = new Entity\ImageCollection;

        // instantiate and map data to new User entity
        $user = new Entity\User;
        $user->setId($creator);

        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);
        $userMapper->fetch($user);

        // no valid User
        if (!$user->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $imageCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        // stateObserver stuff

        // clientState stuff
    }

    /**
     * Lookup images by Group (e.g. tag, content category)
     *
     * @param  int $groupID
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\ImageCollection
     */
    public function findByGroup(int $groupID)
    {
        $imageCollection = new Entity\ImageCollection;

        // instantiate and map data to new Group entity
        $group = new Entity\Group;
        $group->setId($groupID);

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\Group::class);
        $groupMapper->fetch($group);

        // no valid Group
        if (!$group->getId()) {
            throw new Exception\IdentityNotFound;
        }

        $imageCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        // map data
        // check whether collection empty, do something

        // do we really want to grab all tag collections ???
        // if we choose not to, we can make a different toArray() method

        // stateObserver stuff

        // clientState stuff
    }
}
