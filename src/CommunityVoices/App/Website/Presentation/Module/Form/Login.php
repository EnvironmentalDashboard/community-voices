<?php

namespace CommunityVoices\App\Website\Presentation\Module\Form;

use \DOMDocument;
use \XSLTProcessor;

class Login
{
    public function generate($params)
    {
        $template = new DOMDocument;
        $template->load(__DIR__ . '/../../Template/Module/Form/Login.xslt');

        $processor = new XSLTProcessor;
        $processor->importStyleSheet($template);

        return $processor->transformToXML($params);
    }
}
