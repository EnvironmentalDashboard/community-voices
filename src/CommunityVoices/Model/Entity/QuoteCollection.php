<?php

namespace CommunityVoices\Model\Entity;

class QuoteCollection extends MediaCollection
{
    public function __construct(){
        $this->mediaType = self::MEDIA_TYPE_QUOTE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity(){
        return new Quote;
    }

    public function toArray()
    {
        return ['quoteCollection' => parent::toArray()['mediaCollection']];
    }

}
