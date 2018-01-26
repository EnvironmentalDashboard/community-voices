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
    public function upload($text, $attribution, $subAttribution,
                    $dateRecorded, $publicDocumentLink, $sourceDocumentLink,
                    $addedBy){

        /*
         * Create Quote entity and set attributes
         */

        $quote = new Entity\Quote;

        $quote->setText($text);
        $quote->setAttribution($attribution);
        $quote->setSubAttribution($subAttribution);
        $quote->setDateRecorded($dateRecorded);
        $quote->setPublicDocumentLink($publicDocumentLink);
        $quote->setSourceDocumentLink($sourceDocumentLink);
        $quote->setAddedBy($addedBy);
        $quote->setStatus(3);                                   // change later

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

        $quoteMapper = $this->mapperFactory->createDataMapper(Mapper\Quote::class);

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
