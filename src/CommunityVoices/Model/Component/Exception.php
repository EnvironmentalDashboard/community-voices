<?php

namespace CommunityVoices\Model\Component;

class Exception extends \Exception
{
    /**
     * @codeCoverageIgnore
     */
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
