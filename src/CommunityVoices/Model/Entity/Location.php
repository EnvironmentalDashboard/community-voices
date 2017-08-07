<?php

namespace CommunityVoices\Model\Entity;

class Location
{
    private $id;

    private $label;

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
}
