<?php

namespace CommunityVoices\Model\Entity;

class ContentCategory extends Group
{
    private $mediaFilename;

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
}
