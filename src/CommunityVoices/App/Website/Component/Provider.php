<?php

namespace CommunityVoices\App\Website\Component;

/**
 * @overview Used by bootstrapping mechanisms to initialize future dependencies.
 * 
 * Most providers will share something with the injector
 */

abstract class Provider {
    protected $injector;

    public function __construct($injector)
    {
        $this->injector = $injector;
    }

    abstract public function init();
}