<?php

namespace CommunityVoices\Model\Component;

class RelationalEntity
{
    protected $relations;

    /**
     * @codeCoverageIgnore
     */
    public function getRelations()
    {
        return $this->relations;
    }
}
