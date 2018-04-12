<?php

namespace CommunityVoices\Model\Entity;

class OrganizationCategory extends Group
{
    public function __construct()
    {
        $this->type = self::TYPE_ORG_CAT;
    }

    public function toArray()
    {
        return ['organizationCategory' => parent::toArray()['group']];
    }
}
