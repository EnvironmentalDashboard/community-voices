<?php

namespace CommunityVoices\Model\Entity;

class Quote extends Media
{
    private $text;

    private $attribution;
    private $dateRecorded;

    private $publicDocumentLink;
    private $sourceDocumentLink;

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;
    }

    public function getAttribution()
    {
        return $this->attribution;
    }

    public function setAttribution($attribution)
    {
        $this->attribution = $attribution;
    }

    public function getDateRecorded()
    {
        return $this->dateRecorded;
    }

    public function setDateRecorded($dateRecorded)
    {
        $input = (int) $dateRecorded;

        if ($input > 0) {
            $this->dateRecorded = $input;
        }
    }

    public function getPublicDocumentLink()
    {
        return $this->publicDocumentLink;
    }

    public function setPublicDocumentLink($publicDocumentLink)
    {
        $this->publicDocumentLink = $publicDocumentLink;
    }

    public function getSourceDocumentLink()
    {
        return $this->sourceDocumentLink;
    }

    public function setSourceDocumentLink($sourceDocumentLink)
    {
        $this->sourceDocumentLink = $sourceDocumentLink;
    }

    /*
    public function validateForUpload(StatusObserver $notifier)
    {
        $isValid = true;

        // @TODO after discussion
    }
    */
}
