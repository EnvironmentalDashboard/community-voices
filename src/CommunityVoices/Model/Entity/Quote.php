<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class Quote extends Media
{
    const ERR_ATTRIBUTION_REQUIRED = 'Quotes must have an attribution.';
    const ERR_SOURCE_LINK_INVALID = 'Source document link must be empty or a valid URL.';
    const ERR_PUBLIC_LINK_INVALID = 'Public document link must be empty or a valid URL.';

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


    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        if (!$this->attribution || empty($this->attribution)) {
            $isValid = false;
            $stateObserver->addEntry('attribution', self::ERR_ATTRIBUTION_REQUIRED);
        }

        if ($this->sourceDocumentLink && !filter_var($this->sourceDocumentLink, FILTER_VALIDATE_URL)) {
            $isValid = false;
            $stateObserver->addEntry('sourceDocumentLink', self::ERR_SOURCE_LINK_INVALID);
        }

        if ($this->publicDocumentLink && !filter_var($this->publicDocumentLink, FILTER_VALIDATE_URL)) {
            $isValid = false;
            $stateObserver->addEntry('publicDocumentLink', self::ERR_PUBLIC_LINK_INVALID);
        }

        return $isValid;
    }

    public function toXml()
    {
        $arr = [
            'id' => $this->id,
            'text' => $this->text
        ];

        $xml = '<quote>';

        foreach ($arr as $key => $value) {
            $xml .= '<' . $key . '>' . $value . '</' . $key . '>';
        }

        $xml .= '</quote>';

        return $xml;
    }
}
