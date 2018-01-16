<?php

namespace CommunityVoices\Model\Entity;

class SlideCollection extends MediaCollection
{

    public function __construct(){
        $this->mediaType = self::MEDIA_TYPE_SLIDE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity(){
        return new Slide;
    }

    public function toArray()
    {
        return ['slideCollection' => parent::toArray()['mediaCollection']];
    }

}
