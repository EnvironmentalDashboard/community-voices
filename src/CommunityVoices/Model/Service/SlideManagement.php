<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class SlideManagement
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
     * Maps a new slide to the database
     * @return Boolean                     [description]
     */
    public function upload($quoteId, $imageId, $contentCategory,
                    $screens, $dateRecorded, $approved, $addedBy) {

        $quote = new Entity\Quote;
        $quote->setId((int) $quoteId);

        $image = new Entity\Image;
        $image->setId((int) $imageId);

        $category = new Entity\ContentCategory;
        $category->setId((int) $contentCategory);

        /*
         * Create Slide entity and set attributes
         */
        $slide = new Entity\Slide;

        $slide->setContentCategory($category);
        $slide->setImage($image);
        $slide->setQuote($quote);
        $slide->setProbability(0);
        $slide->setDecayPercent(0);
        $slide->setDecayEnd(time()+3600);
        $slide->setDecayStart(time());
        // $slide->setDateRecorded($dateRecorded);
        $slide->setAddedBy($addedBy);
        if ($approved) {
            $slide->setStatus(3);
        } else {
            $slide->setStatus(1);
        }

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('slideUpload');
        $isValid = $slide->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state.
         */
        // $this->stateObserver->getEntries() to see errors
        if (!$isValid) // && $this->stateObserver->hasEntry('attribution', $quote::ERR_ATTRIBUTION_REQUIRED)
        {
             $clientState->save($this->stateObserver);
             return false;
         }

        $slideMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $slide to database
         */
        $slideMapper->save($slide);

        $locMapper = $this->mapperFactory->createDataMapper(Mapper\Location::class);
        foreach ($screens as $screenId) {
            $locMapper->link($slide->getId(), $screenId);
        }

        return true;

    }

    public function update(int $id, int $imageId, int $quoteId, int $contentCategory, array $screens, int $decay_percent, float $probability, string $decay_start, string $decay_end, int $status, $addedBy) {

        $quote = new Entity\Quote;
        $quote->setId((int) $quoteId);

        $image = new Entity\Image;
        $image->setId((int) $imageId);

        $category = new Entity\ContentCategory;
        $category->setId((int) $contentCategory);

        /*
         * Create Slide entity and set attributes
         */
        $slide = new Entity\Slide;

        $slide->setId($id);
        $slide->setContentCategory($category);
        $slide->setImage($image);
        $slide->setQuote($quote);
        $slide->setProbability((int) $probability);
        $slide->setDecayPercent((int) $decay_percent);
        $slide->setDecayEnd((int) strtotime($decay_end));
        $slide->setDecayStart((int) strtotime($decay_start));
        // $slide->setDateRecorded($dateRecorded);
        $slide->setAddedBy($addedBy);
        $slide->setStatus($status);

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('slideUpload');
        $isValid = $slide->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state.
         */
        // $this->stateObserver->getEntries() to see errors
        if (!$isValid) // && $this->stateObserver->hasEntry('attribution', $quote::ERR_ATTRIBUTION_REQUIRED)
        {
             $clientState->save($this->stateObserver);
             return false;
         }

        $slideMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        /*
         * save $slide to database
         */
        $slideMapper->save($slide);

        $locMapper = $this->mapperFactory->createDataMapper(Mapper\Location::class);
        $locMapper->unselectAllScreens($slide->getId());
        foreach ($screens as $screenId) {
            $locMapper->link($slide->getId(), $screenId);
        }

        return true;

    }

    public function delete($id) {
        $slideMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);
        // $tagMapper = $this->mapperFactory->createDataMapper(Mapper\GroupCollection::class);

        $slide = new Entity\Slide;
        $slide->setId((int) $id);

        // $slideMapper->fetch($slide);

        // $tagMapper->deleteTags($slide);
        $slideMapper->delete($slide);

        return true;
    }

}
