<?php

namespace CommunityVoices\Model\Entity;

class QuoteCollection extends MediaCollection
{
    const FILTER_TYPE_BOUNDARY = 1;

    private $filterType;

    /**
     * For boundary (next/prev) conditioning
     * @var Entity\Quote The anchor quote
     */
    private $anchorQuote;

    public function __construct()
    {
        $this->mediaType = self::MEDIA_TYPE_QUOTE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Quote;
    }

    public function toArray()
    {
        return ['quoteCollection' => parent::toArray()['mediaCollection']];
    }

    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;
    }

    public function getFilterType()
    {
        return $this->filterType;
    }

    public function setAnchorQuote(Quote $quote)
    {
        $this->anchorQuote = $quote;
    }

    public function getAnchorQuote()
    {
        return $this->anchorQuote;
    }
}
