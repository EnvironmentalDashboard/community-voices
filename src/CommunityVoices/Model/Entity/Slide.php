<?php

namespace CommunityVoices\Model\Entity;

class Slide extends Media
{
    const ERR_PROBABILITY_OUT_OF_RANGE = 'Probability must be between 0 and 1';

    const ERR_DECAY_OUT_OF_RANGE = 'Decay must be between 0 and 1';
    const ERR_DECAY_MUST_END = 'Decay must have scheduled end';
    const ERR_DECAY_MUST_END_FUTURE = 'Scheduled end date must be in future';
    const ERR_DECAY_RANGE_INVALID = 'Decay must begin before it ends';

    const ERR_IMAGE_RELATIONSHIP_MISSING = 'Image relationship missing';
    const ERR_QUOTE_RELATIONSHIP_MISSING = 'Quote relationship missing';
    const ERR_CONTENT_CATEGORY_RELATIONSHIP_MISSING = 'Contant category relationship missing';

    private $mediaId;

    private $contentCategoryId;

    private $imageId;
    private $quoteId;

    private $probability = 1;
    private $decayPercent = 0;

    /**
     * A null decay with decay enabled indicates decay begins now
     */
    private $decayStart = null;
    private $decayEnd = null;

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

    public function getDecayEnd()
    {
        return $this->decayEnd;
    }

    public function setDecayEnd($decayEnd)
    {
        $this->decayEnd = $decayEnd;
    }

    public function getDecayStart()
    {
        return $this->decayStart;
    }

    public function setDecayStart($decayStart)
    {
        $this->decayStart = $decayStart;
    }

    public function validateForUpload(StatusObserver $notifier)
    {
        $isValid = true;

        /**
         * Check probability
         */
        if ($this->probability > 1 || $this->probability < 0) {
            $isValid = false;
            $notifier->addEntry('probability', self::ERR_PROBABILITY_OUT_OF_RANGE);
        }

        /**
         * Check decay settings
         */
        if ($this->decayPercent > 1 || $this->decayPercent < 0) {
            $isValid = false;
            $notifier->addEntry('decayPercent', self::ERR_DECAY_OUT_OF_RANGE);
        }

        if ($this->decayPercent > 0) {
            //Slides that decay must have a decay end time
            if ($this->decayEnd === false) {
                $isValid = false;
                $notifier->addEntry('decayEnd', self::ERR_DECAY_MUST_END);
            }

            //Slides that decay must decay in the future
            if ($this->decayEnd < time()) {
                $isValid = false;
                $notifier->addEntry('decayEnd', self::ERR_DECAY_MUST_END_FUTURE);
            }

            //Slides that have a decay begin date must begin decay before they
            //end decay
            if ($this->decayStart !== false && $this->decayStart > $this->decayEnd) {
                $isValid = false;
                $notifier->addEntry('decayStart', self::ERR_DECAY_RANGE_INVALID);
            }
        }

        /**
         * Verify relationships aren't null
         */
        if ($this->imageId === false) {
            $isValid = false;
            $notifier->addEntry('imageId', self::ERR_IMAGE_RELATIONSHIP_MISSING);
        }

        if ($this->quoteId === false) {
            $isValid = false;
            $notifier->addEntry('quoteId', self::ERR_QUOTE_RELATIONSHIP_MISSING);
        }

        if ($this->contentCategoryId === false) {
            $isValid = false;
            $notifier->addEntry('contentCategoryId', self::ERR_CONTENT_CATEGORY_RELATIONSHIP_MISSING);
        }
    }
}
