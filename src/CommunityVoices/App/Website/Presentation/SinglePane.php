<?php

namespace CommunityVoices\App\Website\Presentation;

use \DOMDocument;
use \XSLTProcessor;

class SinglePane
{
    public function generate($params)
    {
        $template = new DOMDocument;
        $template->load(__DIR__ . '/Template/Main.xslt');

        $processor = new XSLTProcessor;
        $processor->importStyleSheet($template);

        return $processor->transformToXML($params);
    }
}
