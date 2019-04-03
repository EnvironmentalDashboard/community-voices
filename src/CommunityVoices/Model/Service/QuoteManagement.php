<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class QuoteManagement
{
    private $mapperFactory;
    private $stateObserver;

    /**
     * @param MapperFactory $mapperFactory
     * @param StateObserver $stateObserver
     */
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
    }

    /**
     * Maps a new quote to the database
     * @param  String $text               [description]
     * @param  String $attribution        [description]
     * @param  String $subAttribution     [description]
     * @param  String $dateRecorded       [description]
     * @param  String $publicDocumentLink [description]
     * @param  String $sourceDocumentLink [description]
     * @return Boolean                     [description]
     */
    public function upload(
        $text,
        $attribution,
        $subAttribution,
        $dateRecorded,
        $approved,
        $addedBy,
        $tags,
        $contentCategories
    ) {

        /*
         * Create Quote entity and set attributes
         */

        $quote = new Entity\Quote;

        $quote->setText($text);
        $quote->setAttribution($attribution);
        $quote->setSubAttribution($subAttribution);
        $quote->setDateRecorded($dateRecorded);
        $quote->setAddedBy($addedBy);
        if ($approved) {
            $quote->setStatus(3);
        } else {
            $quote->setStatus(1);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('quoteUpload');
        $isValid = $quote->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $quote to database
         */

        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $quoteMapper->save($quote);

        $qid = $quote->getId();

        $tagCollection = new Entity\GroupCollection;
        if (is_array($tags)) {
            foreach ($tags as $tid) {
                $tag = new Entity\Tag;
                $tag->setMediaId($qid);
                $tag->setGroupId($tid);
                $tagCollection->addEntity($tag);
            }
        }

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupMapper->saveGroups($tagCollection);

        $contentCategoryCollection = new Entity\GroupCollection;
        if (is_array($contentCategories)) {
            foreach ($contentCategories as $ccid) {
                $contentCategory = new Entity\ContentCategory;
                $contentCategory->setMediaId($qid);
                $contentCategory->setGroupId($ccid);
                $contentCategoryCollection->addEntity($contentCategory);
            }
        }

        $groupMapper->saveGroups($contentCategoryCollection);

        return true;
    }

    public function update(
        $id,
        $text,
        $attribution,
        $subAttribution,
        $dateRecorded,
        $status,
        $tags,
        $contentCategories
    ) {
        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);

        /*
         * Create Quote entity and set attributes
         */

        $quote = new Entity\Quote;
        $quote->setId((int) $id);

        $quoteMapper->fetch($quote);

        if ($text != null) {
            $quote->setText($text);
        }
        if ($attribution != null) {
            $quote->setAttribution($attribution);
        }
        if ($subAttribution != null) {
            $quote->setSubAttribution($subAttribution);
        }
        if ($dateRecorded != null) {
            $quote->setDateRecorded($dateRecorded);
        }
        if ($status != null) {
            $quote->setStatus($status);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('quoteUpdate');
        $isValid = $quote->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $quote to database
         */

        $quoteMapper->save($quote);

        // Save the quote's associated tags.
        $qid = $quote->getId();
        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupMapper->deleteGroups($quote);

        if (is_array($tags)) {
            $tagCollection = new Entity\GroupCollection;
            foreach ($tags as $tid) {
                $tag = new Entity\Tag;
                $tag->setMediaId($qid);
                $tag->setGroupId($tid);
                $tagCollection->addEntity($tag);
            }
            $groupMapper->saveGroups($tagCollection);
        }

        if (is_array($contentCategories)) {
            $contentCategoryCollection = new Entity\GroupCollection;
            foreach ($contentCategories as $ccid) {
                $contentCategory = new Entity\ContentCategory;
                $contentCategory->setMediaId($qid);
                $contentCategory->setGroupId($ccid);
                $contentCategoryCollection->addEntity($contentCategory);
            }
            $groupMapper->saveGroups($contentCategoryCollection);
        }

        return true;
    }

    public function delete($id)
    {
        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);

        $quote = new Entity\Quote;
        $quote->setId((int) $id);

        $tagMapper->deleteGroups($quote);
        $quoteMapper->delete($quote);

        return true;
    }

    public function unpair($quote_id, $slide_id)
    {
        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);
        $quote = new Entity\Quote;
        $quote->setId((int) $quote_id);
        $slide = new Entity\Slide;
        $slide->setId((int) $slide_id);
        $quoteMapper->unpair($quote, $slide);
    }
}
