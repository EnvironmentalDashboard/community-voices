<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;

/**
 * @overview Logger provider
 *
 * Used by Palladium
 */

class Injector extends Provider
{
    protected $injector;

    public function __construct($injector)
    {
        parent::__construct($injector);

        $this->injector = $injector;
    }

    public function init()
    {
        $this->injector->share($this->injector);
    }
}
