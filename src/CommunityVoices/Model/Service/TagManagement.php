<?php

namespace CommunityVoices\Model\Service;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class TagManagement
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

    public function upload(
        $label
    ) {
        $tag = new Entity\Tag;

        $tag->setLabel($label);

        // Error observer
        $this->stateObserver->setSubject('tagUpload');
        $tag->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\Tag::class);

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        $tagMapper->save($tag);
        return true;
    }

    public function delete($id)
    {
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\Tag::class);

        $tag= new Entity\Tag;
        $tag->setId((int) $id);

        $tagMapper->delete($tag);

        return true;
    }
}
