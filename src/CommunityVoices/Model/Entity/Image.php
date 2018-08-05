<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;

class Image extends Media
{
    private $filename; /* @TODO required */

    private $title;
    private $description;
    private $generatedTags;

    private $dateTaken;
    private $photographer;
    private $organization;

    public function __construct()
    {
        $this->type = self::TYPE_IMAGE;
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
        if (is_numeric($dateTaken)) {
            $input = (int) $dateTaken;
        } else {
            $input = strtotime($dateTaken);
        }

        if ($input > 0) {
            $this->dateTaken = $input;
        }
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

    public function getCropRect()
    {
        return $this->cropRect;
    }

    public function setCropRect($rect)
    {
        if (is_array($rect)) {
            $this->cropRect = $rect;
        } elseif (is_string($rect)) {
            $parts = explode(',', $rect);
            $this->cropRect = [
                'x' => (int) $parts[0],
                'y' => (int) $parts[1],
                'height' => (int) $parts[2],
                'width' => (int) $parts[3]
            ];
        }
    }

    public function toArray()
    {
        return ['image' => array_merge(parent::toArray()['media'], [
            'filename' => $this->filename,
            'title' => $this->title,
            'description' => $this->description,
            'generatedTags' => $this->generatedTags,
            'dateTaken' => date('Y-m-d H:i:s', $this->dateTaken),
            'photographer' => $this->photographer,
            'organization' => $this->organization
        ])];
    }

    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        // add cases where it wouldn't be valid!!

        return $isValid;
    }

}
