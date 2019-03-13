<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class Display404 extends Component\View
{
    public function get404()
    {
        /**
         * Display404 XML Package
         */
        $display404PackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        /**
         * Generate Display404 module
         */
        $display404Module = new Component\Presenter('Module/Display404');
        $display404ModuleXML = $display404Module->generate($display404PackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $display404ModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
