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

    // public function getFormattedText()
    // {
    //     return $this->formattedText;
    // }

    // public function setFormattedText($textOrQuote, $attributionOrNull = null)
    // {
    //     if ($textOrQuote instanceof Quote) {
    //         $this->formattedText = $this->formatText($textOrQuote->getText(), $textOrQuote->getAttribution());
    //     } else {
    //         $this->formattedText = $this->formatText($textOrQuote, $attributionOrNull);
    //     }
    // }

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

    private function formatText(string $text, string $attribution, float $image_end) {
        $space_left = 100 - $image_end;
        $font_size = $this->convertRange($space_left, 0, 100, 2.7, 3.7);
        $every = round($this->convertRange($space_left, 0, 100, 14, 25));
        $counter = 0;
        $len = strlen($text);
        $ret = '<text font-family="Comfortaa, Helvetica, sans-serif" x="'.$image_end.'px" y="'.(10 + ( (10/$len) * 100 )).'%" fill="#fff" font-size="'.$font_size.'px"><tspan>';
        foreach (str_split($text) as $char) {
            if ($counter++ > $every && $char === ' ') {
                $counter = 0;
                $ret .= '</tspan><tspan x="'.$image_end.'px" dy="4">';
            }
            $ret .= $char;
        }
        $ret .= '</tspan><tspan font-size="2px" x="'.$image_end.'px" dy="5">&#8212; ';
        $once = 0;
        if (strlen($attribution) > 10) {
            foreach (explode(',', $attribution) as $part) {
                if ($once++ === 1) {
                    $ret .= ',</tspan><tspan font-size="2px" x="'.($image_end+2).'px" dy="2">';
                }
                $ret .= $part;
            }
        }
        return $ret . '</tspan></text>';
    }

    private function format(Image $image, Quote $quote) {
        $fn = $image->getFilename();
        if (file_exists($fn)) { // it wont exist on local
            $max_height = 39; // viewBox height is 50px, but minus 7px for content category banner and 4px for margin around image
            $max_width = 56; // viewBox width is 100px, but image should take at most 60% of space, minus 4px for margin
            $size = getimagesize($fn);
            $w = $size[0];
            $h = $size[1];
            $aspect_ratio = $w/$h;
            $max_aspect_ratio = $max_width/$max_height;
            $final_height = $max_height;
            $final_width = ($final_height * $aspect_ratio);
            if ($final_width > $max_width) {
                $final_width = $max_width;
            }
            $final_y = 2; // 2px on each side = 4px of margin total
            if ($final_width != $max_width) {
                $final_x = ($max_width - $final_width) / 4;
            } else {
                $final_x = 2;
            }
            $image_href = 'data:' . mime_content_type($fn) . ';base64,' . base64_encode(file_get_contents($fn));
        } else {
            $final_y = 10;
            $final_x = 10;
            $final_width = 35;
            $image_href = 'https://environmentaldashboard.org/cv/uploads/'.$image->getId();
        }
        return '<image x="'.$final_x.'px" y="'.$final_y.'px" width="'.$final_width.'px" xlink:href="'.$image_href.'"></image>' . $this->formatText($quote->getText(), $quote->getAttribution(), $final_width + ($final_x*2));

    }

    private function convertRange($val, $old_min, $old_max, $new_min, $new_max) {
        return ((($new_max - $new_min) * ($val - $old_min)) / ($old_max - $old_min)) + $new_min;
      }

    public function toArray()
    {
        return ['slide' => array_merge(parent::toArray()['media'], [
            'contentCategory' => $this->contentCategory ? $this->contentCategory->toArray() : null,
            'image' => $this->image ? $this->image->toArray() : null,//$this->image ? $this->image->toArray() : null,
            'quote' => $this->quote ? $this->quote->toArray() : null,//$this->quote ? $this->quote->toArray() : null,
            'g' => $this->format($this->image, $this->quote),
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
