<?php

namespace CommunityVoices\App\Website\Component;

use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Entity;
use CommunityVoices\App\Api\Controller;

/**
 * @codeCoverageIgnore
 */
class Presenter
{
    private $presentationPath;

    public function __construct($presentationPath)
    {
        $this->presentationPath = $presentationPath;
    }

    public function generate($params)
    {
        $template = new DOMDocument;
        $template->load(__DIR__ . '/../Presentation/' . $this->presentationPath . '.xslt');

        $processor = new XSLTProcessor;
        
        $processor->importStyleSheet($template);

        return $processor->transformToXML($params);
    }
}
