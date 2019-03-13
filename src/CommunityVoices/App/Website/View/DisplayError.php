<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class DisplayError extends Component\View
{
    public function getError()
    {
        /**
         * DisplayError XML Package
         */
        $displayErrorPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        /**
         * Generate DisplayError module
         */
        $displayErrorModule = new Component\Presenter('Module/DisplayError');
        $displayErrorModuleXML = $displayErrorModule->generate($displayErrorPackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $displayErrorModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
