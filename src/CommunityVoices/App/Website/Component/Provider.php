<?php

namespace CommunityVoices\App\Website\Component;

use Auryn;

/**
 * @overview Used by bootstrapping mechanisms to initialize dependencies that must
 * be configured/initialized in specific ways.
 *
 * Providers in this application are defined by the fact they must interact with
 * the injector. Interactions with the injector may fall under a few categories:
 *
 * 1. Sharing an instance (`$injector->share($obj)`): sharing an instance tells
 *    the injector to use a pre-existing object when objects of type $obj are
 *    auto-injected;
 *
 *    Only use instance sharing if the provided subject is universal and must be
 *    instantiated at every script run.
 *
 * 2. Defining instance instantiation (`$injector->define('Foo', ['bar' => 'Hi'])`):
 *    Defining instance instantiaton provides the injector default values to use
 *    for constructor argument variables. The keys of the definition array must
 *    match the argument names.
 *
 * 3. Delegating instance instantiation (`$injector->delegate('Foo', $callback)`):
 *    delegating instance instatiation uses the callable factory method $callback
 *    to instantiate an instance of `Foo`.
 *
 *    Use instance delegation if an instance requires particular intialization via
 *    methods other than the constructor. If it does not, use "Defining instance
 *    instantiation."
 *
 * CommunityVoices uses auryn. For more options & info: https://github.com/rdlowrey/auryn
 */

abstract class Provider
{
    protected $injector;

    public function __construct(Auryn\Injector $injector)
    {
        $this->injector = $injector;
    }

    abstract public function init();
}
