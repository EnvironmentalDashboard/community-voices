<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for slide entities.
 */

class SlideLookup
{

    /**
     * Lookup slide by id
     *
     * @param  int $slideId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\Slide
     */
    public function findById(int $slideId)
    {
    }

    /**
     * Lookup group by id
     *
     * @param  int $groupId
     *
     * @throws CommunityVoices\Model\Exception\IdentityNotFound
     *
     * @return CommunityVoices\Model\Entity\SlideCollection
     */
    public function findByGroup(int $groupId)
    {
    }
}
