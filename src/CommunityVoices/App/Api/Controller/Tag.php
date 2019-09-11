<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Service\TagLookup;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api\Component;

class Tag extends Component\Controller
{
    protected $tagLookup;

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        StateObserver $stateObserver,

        Service\TagLookup $tagLookup
    ) {
        parent::__construct($identifier, $logger, $stateObserver);

        $this->tagLookup = $tagLookup;
    }

    protected function getAllTag()
    {
        $this->tagLookup->findAll();
    }
}
