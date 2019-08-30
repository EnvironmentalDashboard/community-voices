<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\App\Api\Component;

class DisplayError extends Component\Controller
{
    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger
    ) {
        parent::__construct($identifier, $logger);
    }

    public static function CANgetError()
    {
        return true;
    }

    protected function getError()
    {

    }
}
