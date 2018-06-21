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
                    $dateRecorded, $approved,
                    $addedBy) {

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

        return true;

    }

    public function update($id, $text, $attribution, $subAttribution,
                    $dateRecorded, $status)
        {

        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);

        /*
         * Create Slide entity and set attributes
         */

        $quote = new Entity\Slide;
        $quote->setId((int) $id);

        $quoteMapper->fetch($quote);

        $quote->setText($text);
        $quote->setAttribution($attribution);
        $quote->setSubAttribution($subAttribution);
        $quote->setDateRecorded($dateRecorded);
        $quote->setStatus($status);

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('quoteUpdate');
        $isValid = $quote->validateForUpload($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * Stop the upload process and save errors to the application state. If
         * there is no attribution, there is no point in continuing the upload process.
         */

       if (!$isValid && $this->stateObserver->hasEntry('attribution', $quote::ERR_ATTRIBUTION_REQUIRED))
        {
             $clientState->save($this->stateObserver);
             return false;
         }

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

        return true;
    }
}
