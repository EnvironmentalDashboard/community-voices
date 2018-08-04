<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Component\Collection;
use CommunityVoices\Model\Contract\HasId;

class MediaCollection extends Collection
{
    const MEDIA_TYPE_QUOTE = 1;
    const MEDIA_TYPE_IMAGE = 2;
    const MEDIA_TYPE_SLIDE = 3;
    const MEDIA_TYPE_ARTICLE = 4;

    protected $allowableMediaType = [
        self::MEDIA_TYPE_QUOTE,
        self::MEDIA_TYPE_IMAGE,
        self::MEDIA_TYPE_SLIDE,
        self::MEDIA_TYPE_ARTICLE
    ];

    protected $mediaType;

    public $creators;
    public $status;

    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new Media;
    }

    /**
     * Sets the media type.
     *
     * @param Integer $type integer representing the media type
     */
    public function forMediaType($type)
    {
        if (in_array($type, $this->allowableMediaType, true)) {
            $this->mediaType = $type;
        }
    }

    /**
     * Returns the media type.
     */
    public function getMediaType()
    {
        return $this->mediaType;
    }

    /**
     * Returns the information contained within the MediaCollection entity object
     * as an array.
     */
    public function toArray()
    {
        $toReturn = ['mediaCollection' => []];

        foreach ($this->collection as $item) {
            $toReturn['mediaCollection'][] = $item->toArray();
        }

        return $toReturn;
    }

    /**
     * Return conjunction of two MediaCollection
     *
     * @param a second MediaCollection $item
     *
     * @return the conjunction of two MediaCollection
     */
    public function conjunction(MediaCollection $item)
    {
    }

    /**
     * Return disjunction of two MediaCollection
     *
     * @param a second MediaCollection $item
     *
     * @return the disjunction of two MediaCollection
     */
    public function disjunction(MediaCollection $item)
    {
    }
}
