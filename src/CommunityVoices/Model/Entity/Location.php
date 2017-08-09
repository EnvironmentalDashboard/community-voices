<?php

namespace CommunityVoices\Model\Entity;

class Location
{
    const ERR_LABEL_EXISTS = 'A location with this label already exists.';
    const ERR_LABEL_REQUIRED = 'Locations are required to have a label.';

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
        $input = (int) $id;

        if ($input > 0) {
            $this->id = $input;
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

    public function validateForUpload(StateObserver $notifier)
    {
        $isValid = true;

        if (!$this->label || empty($this->label)) {
            $isValid = false;
            $notifier->addEntry('label', self::ERR_LABEL_REQUIRED);
        }

        return $isValid;
    }
}
