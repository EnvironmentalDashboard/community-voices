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
    protected $mapperFactory;
    protected $transcriber;
    protected $identificationAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->identificationAPIView = $identificationAPIView;
    }

    public function getAccessDenied($request)
    {
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode($this->identificationAPIView->getIdentity()->getContent()))
        );

        /**
         * AccessDenied XML Package
         */
        $accessDeniedPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedIdentity = $accessDeniedPackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

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

        $domainXMLElement->addChild('main-pane', $accessDeniedModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
