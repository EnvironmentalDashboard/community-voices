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
        $originalText,
        $interviewer,
        $attribution,
        $subAttribution,
        $quotationMarks,
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
        $quote->setOriginalText($originalText);
        $quote->setInterviewer($interviewer);
        $quote->setAttribution($attribution);
        $quote->setSubAttribution($subAttribution);
        $quote->setQuotationMarks(is_null($quotationMarks) ? 0 : 1);
        $quote->setDateRecorded($dateRecorded);
        $quote->setAddedBy($addedBy);
        if ($approved) {
            $quote->setStatus(3);
        } else {
            $quote->setStatus(1);
        }

        // There is no reason that we should store a value for originalText if it is the same
        // as text.
        if (strcmp($quote->getText(), $quote->getOriginalText()) == 0) {
            $quote->setOriginalText(null);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('quoteUploadErrors');
        $isValid = $quote->validateForUpload($this->stateObserver, $contentCategories);

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

        // Save our new quote's ID to be used to redirect to it later.
        $this->stateObserver->setSubject('quoteUpload');
        $this->stateObserver->addEntry('id', $qid);
        $clientState->save($this->stateObserver);

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
        array $attributes
    ) {
        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);

        /*
         * Create Quote entity and set attributes
         */

        $quote = new Entity\Quote;
        $quote->setId((int) $id);

        $quoteMapper->fetch($quote);

        /*
         * Using an array of attributes allows
         * us to only change specific attributes
         * that we deem appropriate.
         * Anything without a value in the array
         * will not be changed.
         */
        if (key_exists("text", $attributes)) {
            $quote->setText($attributes["text"]);
        }
        if (key_exists("originalText", $attributes)) {
            $quote->setOriginalText($attributes["originalText"]);
        }
        if (key_exists("interviewer", $attributes)) {
            $quote->setInterviewer($attributes["interviewer"]);
        }
        if (key_exists("attribution", $attributes)) {
            $quote->setAttribution($attributes["attribution"]);
        }
        if (key_exists("subAttribution", $attributes)) {
            $quote->setSubAttribution($attributes["subAttribution"]);
        }
        if (key_exists("dateRecorded", $attributes)) {
            $quote->setDateRecorded($attributes["dateRecorded"]);
        }
        if (key_exists("status", $attributes)) {
            $quote->setStatus($attributes["status"]);
        }

        // There is no reason that we should store a value for originalText if it is the same
        // as text.
        if (strcmp($quote->getText(), $quote->getOriginalText()) == 0) {
            $quote->setOriginalText(null);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        $groupMapper->fetch($quote->getContentCategoryCollection());

        $this->stateObserver->setSubject('quoteUpdate');
        $isValid = $quote->validateForUpload($this->stateObserver, $attributes["contentCategories"] ?? $quote->getContentCategoryCollection()->getCollection());

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

        /*
         * If we are trying to adjust either our tags or content categories,
         * we will need to delete all groups and add the appropriate ones back.
         */
        $addingGroups = key_exists("tags", $attributes) || key_exists("contentCategories", $attributes);
        if ($addingGroups) {
            // We only fetched the content category collection earlier,
            // so we need to fill in the gap here.
            $groupMapper->fetch($quote->getTagCollection());

            // Delete all groups.
            // TODO: delete only the appropriate groups
            $groupMapper->deleteGroups($quote);

            /*
             * For both sets of groups, we will add back
             * either what was already associated with the
             * quote or something new depending on what new
             * data we have sent in the request.
             */
            $tagCollection = new Entity\GroupCollection;
            if (key_exists("tags", $attributes) && is_array($attributes["tags"])) {
                $tagLoop = $attributes["tags"];
            } else {
                $tagLoop = array_map(function ($t) {
                    return $t->getId();
                }, $quote->getTagCollection()->getCollection());
            }

            foreach ($tagLoop as $tid) {
                $tag = new Entity\Tag;
                $tag->setMediaId($qid);
                $tag->setGroupId($tid);
                $tagCollection->addEntity($tag);
            }

            $groupMapper->saveGroups($tagCollection);

            $contentCategoryCollection = new Entity\GroupCollection;
            if (key_exists("contentCategories", $attributes) && is_array($attributes["contentCategories"])) {
                $contentCategoryLoop = $attributes["contentCategories"];
            } else {
                $contentCategoryLoop = array_map(function ($cc) {
                    return $cc->getId();
                }, $quote->getContentCategoryCollection()->getCollection());
            }

            foreach ($contentCategoryLoop as $ccid) {
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
