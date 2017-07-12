<?php

namespace CommunityVoices\Model\Entity;

class Image extends Media
{
    private $mediaId;

    private $filename;

    private $title;
    private $description;
    private $generatedTags;

    private $dateTaken;
    private $photographer;
    private $organization;

    public function getMediaId()
    {
        return $this->mediaId;
    }

    public function setMediaId($mediaId)
    {
        $this->mediaId = $mediaId;
    }

    public function getFilename()
    {
        return $this->filename;
    }

    public function setFilename($filename)
    {
        $this->filename = $filename;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getGeneratedTags()
    {
        return $this->generatedTags;
    }

    public function setGeneratedTags($generatedTags)
    {
        $this->generatedTags = $generatedTags;
    }

    public function getDateTaken()
    {
        return $this->dateTaken;
    }

    public function setDateTaken($dateTaken)
    {
        $this->dateTaken = $dateTaken;
    }

    public function getPhotographer()
    {
        return $this->photographer;
    }

    public function setPhotographer($photographer)
    {
        $this->photographer = $photographer;
    }

    public function getOrganization()
    {
        return $this->organization;
    }

    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }
}
