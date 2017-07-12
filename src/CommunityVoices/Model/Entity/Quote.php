<?php

namespace CommunityVoices\Model\Entity;

class Quote extends Media
{
    private $mediaId;

    private $text;

    private $attribution;
    private $dateRecorded;

    private $publicDocumentLink;
    private $sourceDocumentLink;

	public function getMediaId()
    {
		return $this->mediaId;
	}

	public function setMediaId($mediaId)
    {
		$this->mediaId = $mediaId;
	}

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
		$this->dateRecorded = $dateRecorded;
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
}
