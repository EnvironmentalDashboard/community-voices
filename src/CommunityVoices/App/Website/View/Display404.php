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
    protected $recognitionAdapter;
    protected $mapperFactory;
    protected $locationAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Api\View\Location $locationAPIView,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->locationAPIView = $locationAPIView;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
    }

    public function get404()
    {
        // Identity gathering
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Display404 XML Package
         */
        $display404PackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedIdentity = $display404PackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

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

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
