<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service\TagLookup;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;
use CommunityVoices\App\Api\AccessControl;

class Tag extends Component\Controller
{
    protected $tagLookup;

    public function __construct(
        AccessControl\Tag $tagAccessControl,

        Service\TagLookup $tagLookup
    ) {
        parent::__construct($tagAccessControl);

        $this->tagLookup = $tagLookup;
    }

    protected function getAllTag()
    {
        $this->tagLookup->findAll();
    }
}
