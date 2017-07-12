<?php

namespace CommunityVoices\Model\Entity;

class Slide extends Media
{
    private $mediaId;

    private $contentCategoryId;

    private $imageId;
    private $quoteId;

    private $probability;
    private $decayPercent;
    private $decayUntil;

	public function getMediaId()
    {
		return $this->mediaId;
	}

	public function setMediaId($mediaId)
    {
		$this->mediaId = $mediaId;
	}

	public function getContentCategoryId()
    {
		return $this->contentCategoryId;
	}

	public function setContentCategoryId($contentCategoryId)
    {
		$this->contentCategoryId = $contentCategoryId;
	}

	public function getImageId()
    {
		return $this->imageId;
	}

	public function setImageId($imageId)
    {
		$this->imageId = $imageId;
	}

	public function getQuoteId()
    {
		return $this->quoteId;
	}

	public function setQuoteId($quoteId)
    {
		$this->quoteId = $quoteId;
	}

	public function getProbability()
    {
		return $this->probability;
	}

	public function setProbability($probability)
    {
		$this->probability = $probability;
	}

	public function getDecayPercent()
    {
		return $this->decayPercent;
	}

	public function setDecayPercent($decayPercent)
    {
		$this->decayPercent = $decayPercent;
	}

	public function getDecayUntil()
    {
		return $this->decayUntil;
	}

	public function setDecayUntil($decayUntil)
    {
		$this->decayUntil = $decayUntil;
	}
}
