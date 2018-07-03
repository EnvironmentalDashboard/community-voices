<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class Slide extends Media
{
    /**
     * Note: Slides inherit tags from their image & quote assignments
     */

    const ERR_PROBABILITY_OUT_OF_RANGE = 'Probability must be between 0 and 1';

    const ERR_DECAY_OUT_OF_RANGE = 'Decay must be between 0 and 1';
    const ERR_DECAY_MUST_END = 'Decay must have scheduled end';
    const ERR_DECAY_MUST_END_FUTURE = 'Scheduled end date must be in future';
    const ERR_DECAY_RANGE_INVALID = 'Decay must begin before it ends';

    const ERR_IMAGE_RELATIONSHIP_MISSING = 'Image relationship missing';
    const ERR_QUOTE_RELATIONSHIP_MISSING = 'Quote relationship missing';
    const ERR_CONTENT_CATEGORY_RELATIONSHIP_MISSING = 'Content category relationship missing';

    const ERR_SLIDE_COMBINATION_EXISTS = 'This image-quote pair already exists.';

    private $contentCategory;

    private $image;
    private $quote;
    private $formattedText;

    private $probability = 1;
    private $decayPercent = 0;

    /**
     * A null decay with decay enabled indicates decay begins now
     */
    private $decayStart = null;
    private $decayEnd = null;

    private $organizationCategoryCollection;

    public function __construct()
    {
        $this->type = self::TYPE_SLIDE;
    }

    public function getContentCategory()
    {
        return $this->contentCategory;
    }

    public function setContentCategory(ContentCategory $contentCategory)
    {
        $this->contentCategory = $contentCategory;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage(Image $image)
    {
        $this->image = $image;
    }

    public function getQuote()
    {
        return $this->quote;
    }

    public function setQuote(Quote $quote)
    {
        $this->quote = $quote;
    }

    public function getFormattedText()
    {
        return $this->formattedText;
    }

    public function setFormattedText(string $text)
    {
        $this->formattedText = $text;
    }

    public function createFormattedText(Quote $quote)
    {
        $this->formattedText = $this->format_text($quote->getText());
    }

    public function getProbability()
    {
        return $this->probability;
    }

    public function setProbability($probability)
    {
        $this->probability = (float) $probability;
    }

    public function getDecayPercent()
    {
        return $this->decayPercent;
    }

    public function setDecayPercent($decayPercent)
    {
        $this->decayPercent = (float) $decayPercent;
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

    public function getOrganizationCategoryCollection()
    {
        return $this->organizationCategoryCollection;
    }

    public function setOrganizationCategoryCollection(GroupCollection $organizationCategoryCollection)
    {
        $this->organizationCategoryCollection = $organizationCategoryCollection;
    }

    private function format_text(string $text) {
        $counter = 0;
        $ret = '<tspan>';
        foreach (str_split($text) as $char) {
            if ($counter > 10) {
                $counter = 0;
                $ret .= '</tspan><tspan dy="2">';
            }
            $ret .= $char;
            $counter++;
        }
        return $ret . '</tspan>';
    }

    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        /**
         * Check probability
         */
        if ($this->probability > 1 || $this->probability < 0) {
            $isValid = false;
            $stateObserver->addEntry('probability', self::ERR_PROBABILITY_OUT_OF_RANGE);
        }

        /**
         * Check decay settings
         */
        if ($this->decayPercent > 1 || $this->decayPercent < 0) {
            $isValid = false;
            $stateObserver->addEntry('decayPercent', self::ERR_DECAY_OUT_OF_RANGE);
        }

        if ($this->decayPercent > 0) {
            //Slides that decay must have a decay end time
            if ($this->decayEnd === false) {
                $isValid = false;
                $stateObserver->addEntry('decayEnd', self::ERR_DECAY_MUST_END);
            }

            //Slides that decay must decay in the future
            if ($this->decayEnd < time()) {
                $isValid = false;
                $stateObserver->addEntry('decayEnd', self::ERR_DECAY_MUST_END_FUTURE);
            }

            //Slides that have a decay begin date must begin decay before they
            //end decay
            if ($this->decayStart !== false && $this->decayStart > $this->decayEnd) {
                $isValid = false;
                $stateObserver->addEntry('decayStart', self::ERR_DECAY_RANGE_INVALID);
            }
        }

        /**
         * Verify relationships aren't null
         */
        if (!$this->image || ($this->image instanceof Image && !$this->image->getId())) {
            $isValid = false;
            $stateObserver->addEntry('image', self::ERR_IMAGE_RELATIONSHIP_MISSING);
        }

        if (!$this->quote || ($this->quote instanceof Quote && !$this->quote->getId())) {
            $isValid = false;
            $stateObserver->addEntry('quote', self::ERR_QUOTE_RELATIONSHIP_MISSING);
        }

        if (!$this->contentCategory || ($this->contentCategory instanceof ContentCategory
            && !$this->contentCategory->getId())) {
            $isValid = false;
            $stateObserver->addEntry('contentCategory', self::ERR_CONTENT_CATEGORY_RELATIONSHIP_MISSING);
        }

        return $isValid;
    }

    public function toArray()
    {
        return ['slide' => array_merge(parent::toArray()['media'], [
            'contentCategory' => $this->contentCategory ? $this->contentCategory->toArray() : null,
            'image' => $this->image ? $this->image->getId() : null,//$this->image ? $this->image->toArray() : null,
            'quote' => $this->quote ? $this->quote->toArray() : null,//$this->quote ? $this->quote->toArray() : null,
            'tspan' => $this->formattedText,
            'probability' => $this->probability,
            'decayPercent' => $this->decayPercent,
            'decayStart' => $this->decayStart,
            'decayEnd' => $this->decayEnd,
            'organizationCategoryCollection' => $this->organizationCategoryCollection
                ? $this->organizationCategoryCollection->toArray()
                : null
        ])];
    }
}
