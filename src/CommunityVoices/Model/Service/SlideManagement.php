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
     * Maps a new quote to the database
     * @param  String $text               [description]
     * @param  String $attribution        [description]
     * @param  String $subAttribution     [description]
     * @param  String $dateRecorded       [description]
     * @param  String $publicDocumentLink [description]
     * @param  String $sourceDocumentLink [description]
     * @return Boolean                     [description]
     */
    public function upload($text, $attribution, $subAttribution,
                    $dateRecorded, $approved,
                    $addedBy){

        /*
         * Create Slide entity and set attributes
         */

        $quote = new Entity\Slide;

        $quote->setText($text);
        $quote->setAttribution($attribution);
        $quote->setSubAttribution($subAttribution);
        $quote->setDateRecorded($dateRecorded);
        $quote->setAddedBy($addedBy);
        if($approved){
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
         * Stop the upload process and save errors to the application state. If
         * there is no attribution, there is no point in continuing the upload process.
         */

       if (!$isValid && $this->stateObserver->hasEntry('attribution', $quote::ERR_ATTRIBUTION_REQUIRED))
        {
             $clientState->save($this->stateObserver);
             return false;
         }

        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Slide::class);

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
