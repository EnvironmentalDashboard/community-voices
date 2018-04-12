<?php

namespace CommunityVoices\Model\Entity;

class ImageCollection extends MediaCollection
{

    public function __construct(){
        $this->mediaType = self::MEDIA_TYPE_IMAGE;
    }

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity(){
        return new Image;
    }

    public function toArray()
    {
        return ['imageCollection' => parent::toArray()['mediaCollection']];
    }

}
