<?php

namespace CommunityVoices\App\Api\Controller;

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

        Service\TagLookup $tagLookup
    ) {
        parent::__construct($identifier, $logger);

        $this->tagLookup = $tagLookup;
    }

    public static function CANgetAllTag($user)
    {
        return $user->isRoleAtLeast(Entity\User::ROLE_GUEST);
    }

    protected function getAllTag()
    {
        $this->tagLookup->findAll();
    }
}
