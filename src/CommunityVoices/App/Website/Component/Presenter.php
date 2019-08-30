<?php

namespace CommunityVoices\App\Website\Component;

use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Entity;

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

    // A utility that helps the XSLT reach Access Control.
    // Turns a user DOM element into an instance.
    public static function can($method, $user, $details)
    {
        $entityInstance = new Entity\User();

        foreach ($user[0]->childNodes as $node) {
            $setter = 'set' . ucfirst($node->tagName);

            $entityInstance->{$setter}($node->nodeValue);
        }

        return call_user_func($method, $entityInstance, $details);
    }

    public function generate($params)
    {
        $template = new DOMDocument;
        $template->load(__DIR__ . '/../Presentation/' . $this->presentationPath . '.xslt');

        $processor = new XSLTProcessor;

        // We can only call PHP to reference our access control.
        $processor->registerPHPFunctions('CommunityVoices\App\Website\Component\Presenter::can');

        $processor->importStyleSheet($template);

        return $processor->transformToXML($params);
    }
}
