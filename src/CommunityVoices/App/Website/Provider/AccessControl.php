<?php

namespace CommunityVoices\App\Website\Provider;

use CommunityVoices\App\Website\Component\Provider;
use CommunityVoices\App\Api\Component\Arbiter;

/**
 * @overview Access control provider
 */

class AccessControl extends Provider {
    public function init()
    {
        $aclRaw = json_decode(file_get_contents(__DIR__  . '/../../Api/Config/AccessControlList.json'), true);

        $arbiter = new Arbiter($aclRaw['roles'], $aclRaw['rules']);

        $this->injector->share($arbiter);
    }
}