<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\TagLookup;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Tag extends Component\Controller
{
    protected $tagLookup;

    public function __construct(
        Service\TagLookup $tagLookup
    ) {
        $this->tagLookup = $tagLookup;
    }

    public function getAllTag()
    {
        $this->tagLookup->findAll();
    }
}
