<?php

namespace CommunityVoices\Model\Service;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class ContentCategoryManagement
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
        ?Entity\Image $image,
        $label,
        $color
    ) {
        $contentCategory = new Entity\ContentCategory;

        if($image) {
            $contentCategory->setImage($image);
        }
        $contentCategory->setLabel($label);
        $contentCategory->setColor($color);

        // Error observer
        $this->stateObserver->setSubject('contentCategoryUpload');
        $contentCategory->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
        $contentCategoryMapper = $this->mapperFactory->createDataMapper(Mapper\ContentCategory::class);

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        $contentCategoryMapper->save($contentCategory);
        return true;
    }

    public function update($groupId, ?Entity\Image $image, $label, $color)
    {
        $contentCategoryMapper = $this->mapperFactory->createDataMapper(Mapper\ContentCategory::class);

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setGroupId((int) $groupId);

        $contentCategoryMapper->fetch($contentCategory);

        if (!is_null($image)) {
            $contentCategory->setImage($image);
        }

        $contentCategory->setLabel($label);
        $contentCategory->setColor($color);

        // Error observer
        $this->stateObserver->setSubject('contentCategoryUpdate');
        $contentCategory->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        $contentCategoryMapper->save($contentCategory);
        return true;
    }

    public function delete($id)
    {
        $contentCategoryMapper = $this->mapperFactory->createDataMapper(Mapper\ContentCategory::class);

        $contentCategory = new Entity\ContentCategory;
        $contentCategory->setId((int) $id);

        // It would be better to check if we have slides attached to the Content Category,
        // but we can catch the SQL error to save writing more code.
        try {
            $contentCategoryMapper->delete($contentCategory);
        } catch (\PDOException $e) {
            $this->stateObserver->setSubject('contentCategoryDelete');
            $this->stateObserver->addEntry('slides', 'Content Category is attached to slides');

            $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);
            $clientState->save($this->stateObserver);
        }

        return true;
    }
}
