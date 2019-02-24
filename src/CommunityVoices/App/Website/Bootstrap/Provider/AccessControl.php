<?php

namespace CommunityVoices\App\Website\Bootstrap\Provider;

use CommunityVoices\App\Website\Component\Provider;
use CommunityVoices\App\Api\Component\Arbiter;

/**
 * @overview Access control provider
 */

class AccessControl extends Provider
{
    const ACL_PATH = __DIR__ . '/../../../Api/Config/AccessControlList.json';

    public function init()
    {
        $aclRaw = json_decode(file_get_contents(self::ACL_PATH), true);

        $arbiter = new Arbiter($aclRaw['roles'], $aclRaw['rules']);

        $this->injector->share($arbiter);
    }
}
