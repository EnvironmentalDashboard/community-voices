<?php

namespace CommunityVoices\Model\Entity;

class ContentCategory extends Group
{
    // This is a field in the database, but not sure why it exists
    // and how it is utilized.
    private $mediaFilename;
    private $mediaId;
    private $groupId;

    protected $probability; /* @TODO required, number >= 0 */

    public function __construct()
    {
        $this->type = self::TYPE_CONT_CAT;
    }

    public function setMediaId(int $id)
    {
        $this->mediaId = $id;
    }

    public function getMediaId()
    {
        return $this->mediaId;
    }

    public function setGroupId(int $id)
    {
        $this->groupId = $id;
    }

    public function getGroupId()
    {
        return $this->groupId;
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

    public function toArray()
    {
        return ['contentCategory' => array_merge(parent::toArray()['group'], [
            'mediaFilename' => $this->mediaFilename,
            'probability' => $this->probability
        ])];
    }
}
