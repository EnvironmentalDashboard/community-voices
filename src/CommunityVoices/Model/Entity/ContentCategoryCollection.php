<?php

namespace CommunityVoices\Model\Entity;

use CommunityVoices\Model\Contract\HasId;

class ContentCategoryCollection extends GroupCollection
{
    /**
     * @codeCoverageIgnore
     */
    protected function makeEntity()
    {
        return new ContentCategory;
    }

    public function __construct()
    {
        $this->groupType = self::GROUP_TYPE_CONT_CAT;
    }

    public function toArray()
    {
        return ['contentCategoryCollection' => parent::toArray()['groupCollection']];
    }
}
