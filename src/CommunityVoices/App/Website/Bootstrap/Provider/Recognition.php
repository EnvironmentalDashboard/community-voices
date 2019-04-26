<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;

/**
 * @overview Recognition provider
 */

class Recognition extends Provider
{
    public function init()
    {
        /**
         * Alias CanIdentify depdencies with the local recognition service
         */

        $this->injector->alias('CommunityVoices\App\Api\Component\Contract\CanIdentify', 'CommunityVoices\App\Api\Component\RecognitionAdapter');
    }
}
