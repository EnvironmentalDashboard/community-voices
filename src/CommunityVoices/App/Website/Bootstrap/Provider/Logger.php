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
    public function init()
    {
        $logger = new Monolog\Logger('name');

        /**
         * @config
         */
        $logger->pushHandler(new Monolog\Handler\StreamHandler(__DIR__ . '/../../../../log/access.log'));

        $this->injector->share($logger);
        $this->injector->alias('Psr\Log\LoggerInterface', 'Monolog\Logger');
    }
}
