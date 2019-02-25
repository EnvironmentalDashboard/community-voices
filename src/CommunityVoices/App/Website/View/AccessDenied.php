<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class AccessDenied extends Component\View
{
    public function getAccessDenied()
    {
        /**
         * AccessDenied XML Package
         */
        $accessDeniedPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        /**
         * Generate AccessDenied module
         */
        $accessDeniedModule = new Component\Presenter('Module/AccessDenied');
        $accessDeniedModuleXML = $accessDeniedModule->generate($accessDeniedPackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $accessDeniedAccessModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
