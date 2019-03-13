<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;
use \Monolog;

/**
 * @overview Logger provider
 *
 * Used by Palladium
 */

class Logger extends Provider
{
    protected $logger;

    public function __construct($injector, $logger)
    {
        parent::__construct($injector);

        $this->logger = $logger;
    }

    public function init()
    {
        $this->injector->share($this->logger);
        $this->injector->alias('Psr\Log\LoggerInterface', 'Monolog\Logger');
    }
}
