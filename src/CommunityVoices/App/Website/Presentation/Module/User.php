<?php

namespace CommunityVoices\App\Website\Presentation\Module;

use \DOMDocument;
use \XSLTProcessor;

class User
{
    public function generate($params = null)
    {
        $template = new DOMDocument;
        $template->load(__DIR__ . '/../Template/Module/User.xslt');

        $processor = new XSLTProcessor;
        $processor->importStyleSheet($template);

        return $processor->transformToXML($params);
    }
}
