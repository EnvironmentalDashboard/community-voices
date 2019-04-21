<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;
use CommunityVoices\Model\Component;

/**
 * @overview Url generator providerc
 */

class StateObserver extends Provider
{
    public function init()
    {
        $stateObserver = new Component\StateObserver();

        $this->injector->share($stateObserver);
    }
}
