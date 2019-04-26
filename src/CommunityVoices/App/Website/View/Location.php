<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class Location extends Component\View
{
    protected $mapperFactory;
    protected $locationAPIView;
    protected $identificationAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Api\View\Location $locationAPIView,
        Api\View\Identification $identificationAPIView,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->locationAPIView = $locationAPIView;
        $this->identificationAPIView = $identificationAPIView;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
    }

    public function getAllLocation($request)
    {
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode($identificationAPIView->getIdentity()->getContent()))
        );

        // Location data gathering
        $locationAPIView = $this->secureContainer->contain($this->locationAPIView);

        $locationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $locationAPIView->getAllLocation()->getContent()
            ))
        );

        /**
         * Location XML Package
         */
        $locationPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedlocation = $locationPackageElement->addChild('domain');
        $packagedlocation->adopt($locationXMLElement);

        $packagedIdentity = $locationPackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate Location module
         */
        $locationModule = new Component\Presenter('Module/LocationCollection');
        $locationModuleXML = $locationModule->generate($locationPackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $locationModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
