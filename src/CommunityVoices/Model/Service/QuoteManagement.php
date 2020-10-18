<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class QuoteManagement extends Management
{
    private $mapperFactory;
    private $stateObserver;

    const FORM_ATTRIBUTES = [
        'text',
        'originalText',
        'interviewer',
        'attribution',
        'subAttribution',
        'quotationMarks',
        'dateRecorded',
        'status'
    ];

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

    public function save(
        $id,
        array $attributes,
        $identity = null,
        array $oberlinMetaData = null
    ) {
        $isUpload = is_null($id);
        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);

        /*
         * Create Quote entity and set attributes
         */

        $quote = new Entity\Quote;

        // Since this function will either upload or update, we will pick what to do
        // on if $id has a value or not.
        if (!$isUpload) {
            $quote->setId((int) $id);
            $quoteMapper->fetch($quote);
        }

        /*
         * Using an array of attributes allows
         * us to only change specific attributes
         * that we deem appropriate.
         * Anything without a value in the array
         * will not be changed.
         */
        $this->setEntityAttributes($quote, $attributes, self::FORM_ATTRIBUTES);

        if ($isUpload) {
            $quote->setAddedBy($identity);
        }

        // There is no reason that we should store a value for originalText if it is the same
        // as text.
        if (strcmp($quote->getText(), $quote->getOriginalText()) == 0) {
            $quote->setOriginalText(null);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        // We should only get content categories on update, as nothing is attached to nothing.
        $groupMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);
        if (!$isUpload) {
            $groupMapper->fetch($quote->getContentCategoryCollection());
        }

        $this->stateObserver->setSubject('quoteFormErrors');
        $isValid = $quote->validateForUpload($this->stateObserver, $attributes["contentCategories"] ?? $quote->getContentCategoryCollection()->getCollection());

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasSubjectEntries('quoteFormErrors')) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $quote to database
         */
        $quoteMapper->save($quote, $oberlinMetaData);

        // Save the quote's associated tags.
        $qid = $quote->getId();

        // Save our new quote's ID to be used to redirect to it later.
        // This will only be done on upload, hence the is_null
        if ($isUpload) {
            $this->stateObserver->setSubject('quoteUpload');
            $this->stateObserver->addEntry('id', $qid);
            $clientState->save($this->stateObserver);
        }

        /*
         * If we are trying to adjust either our tags or content categories,
         * we will need to delete all groups and add the appropriate ones back.
         */
        $addingGroups = key_exists("tags", $attributes) || key_exists("contentCategories", $attributes);
        if ($addingGroups) {
            // We should only delete groups on update.
            if (!$isUpload) {
                // We only fetched the content category collection earlier,
                // so we need to fill in the gap here.
                $groupMapper->fetch($quote->getTagCollection());

                // Delete all groups.
                // TODO: delete only the appropriate groups
                $groupMapper->deleteGroups($quote);
            }

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
