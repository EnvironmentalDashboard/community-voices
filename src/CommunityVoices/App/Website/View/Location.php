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
    protected $locationAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider,
        Api\View\Location $locationAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        $this->locationAPIView = $locationAPIView;
    }

    public function getAllLocation($request)
    {
        // Location data gathering
        $locationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->locationAPIView->getAllLocation()->getContent()
            ))
        );

        /**
         * Location XML Package
         */
        $locationPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedlocation = $locationPackageElement->addChild('domain');
        $packagedlocation->adopt($locationXMLElement);

        $packagedIdentity = $locationPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

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
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
