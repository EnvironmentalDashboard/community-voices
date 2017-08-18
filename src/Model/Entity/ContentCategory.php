<?php

namespace CommunityVoices\Model\Entity;

class ContentCategory extends Group
{
    private $mediaFilename;

    protected $probability; /* @TODO required, number >= 0 */

    public function __construct()
    {
        $this->type = self::TYPE_CONT_CAT;
    }

    public function getMediaFilename()
    {
        return $this->mediaFilename;
    }

    public function setMediaFilename($mediaFilename)
    {
        $this->mediaFilename = $mediaFilename;
    }

    public function setProbability($probability)
    {
        $this->probability = $probability;
    }

    public function getProbability()
    {
        return $this->probability;
    }
}
