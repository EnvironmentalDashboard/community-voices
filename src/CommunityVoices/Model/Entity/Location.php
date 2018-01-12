<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\FlexibleObserver;
use CommunityVoices\Model\Contract\HasId;

class Location implements HasId
{
    const ERR_LABEL_EXISTS = 'A location with this label already exists.';
    const ERR_LABEL_REQUIRED = 'Locations are required to have a label (1 character minimum).';

    private $id;

    private $label;

    /**
     * @todo how to validate these two -- domain or business ?
     */
    protected $organizationCategoryCollection;
    protected $contentCategoryCollection;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        if (is_int($id) || is_null($id)) {
            $this->id = $id;
        }
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function getOrganizationCategoryCollection()
    {
        return $this->organizationCategoryCollection;
    }

    public function setOrganizationCategoryCollection(GroupCollection $organizationCategoryCollection)
    {
        $this->organizationCategoryCollection = $organizationCategoryCollection;
    }

    public function getContentCategoryCollection()
    {
        return $this->contentCategoryCollection;
    }

    public function setContentCategoryCollection(GroupCollection $contentCategoryCollection)
    {
        $this->contentCategoryCollection = $contentCategoryCollection;
    }

    public function validateForUpload(FlexibleObserver $stateObserver)
    {
        $isValid = true;

        if (!$this->label || empty($this->label)) {
            $isValid = false;
            $stateObserver->addEntry('label', self::ERR_LABEL_REQUIRED);
        }

        return $isValid;
    }

    public function toArray()
    {
        return ['location' => [
            'id' => $this->id,
            'label' => $this->label,
            'organizationCategoryCollection' => $this->organizationCategoryCollection->toArray(),
            'contentCategoryCollection' => $this->contentCategoryCollection->toArray()
        ]];
    }
}
