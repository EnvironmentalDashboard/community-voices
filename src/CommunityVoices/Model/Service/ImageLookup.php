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

        header('Content-type: ' . mime_content_type($fn));
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

        $tags = new Entity\GroupCollection;
        $tags->forGroupType(1);
        $tags->forParent($image);
        $image->setTagCollection($tags);

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
    public function findAll(int $page, int $limit, int $offset, string $order, int $only_unused, string $search, $tags, $photographers, $orgs)
    {
        $imageCollection = new Entity\ImageCollection;
        $imageCollection->setPage($page);
        $imageCollection->setLimit($limit);
        $imageCollectionPhotographers = new \stdClass();
        $imageCollectionOrgs = new \stdClass();

        $imageCollectionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        $imageCollectionMapper->fetch($imageCollection, $only_unused, $search, $tags, $photographers, $orgs, $limit, $offset, $order);
        $imageCollectionMapper->photographers($imageCollectionPhotographers);
        $imageCollectionMapper->orgs($imageCollectionOrgs);

        $tagLookup = new TagLookup($this->mapperFactory, $this->stateObserver);
        $tagLookup->findAll();

        // I am uncertain about this
        $this->stateObserver->setSubject('imageFindAll');
        $this->stateObserver->addEntry('imageCollection', $imageCollection);
        $this->stateObserver->addEntry('imageCollectionPhotographers', $imageCollectionPhotographers);
        $this->stateObserver->addEntry('imageCollectionOrgs', $imageCollectionOrgs);

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

    public function photographers($stateObserver, $return = false) {
        $attributionCollection = new \stdClass;
        $attributionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        $attributionMapper->photographers($attributionCollection);
        $stateObserver->setSubject('imageLookup');
        $stateObserver->addEntry('photographer', $attributionCollection);
        if ($return) {
            return $stateObserver;
        }
        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }

    public function orgs($stateObserver, $return = false) {
        $attributionCollection = new \stdClass;
        $attributionMapper = $this->mapperFactory->createDataMapper(Mapper\ImageCollection::class);
        $attributionMapper->orgs($attributionCollection);
        $stateObserver->setSubject('imageLookup');
        $stateObserver->addEntry('org', $attributionCollection);
        if ($return) {
            return $stateObserver;
        }
        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $clientState->save($stateObserver);
    }

    public function relatedSlide(int $image_id) {
        $mapper = $this->mapperFactory->createDataMapper(Mapper\Image::class);
        $id = $mapper->relatedSlideId($image_id);
        return $id;
        // $slide = new Entity\Slide;
        // $slide->setId($id);
        // $mapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);
        // $mapper->fetch($slide);
        // return $slide;
    }
}
