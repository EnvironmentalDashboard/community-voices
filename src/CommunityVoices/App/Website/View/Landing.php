<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Landing extends Component\View
{
    protected $recognitionAdapter;
    protected $landingAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Landing $landingAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->landingAPIView = $landingAPIView;
    }

    public function getLanding($routes, $context){
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather landing information
         */
        $landingAPIView = $this->secureContainer->contain($this->landingAPIView);
        $json = json_decode($landingAPIView->getLanding()->getContent());
        $obj = new \stdClass;
        $obj->slideCollection = array_values((array) $json->slideCollection);

        $landingXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($obj)
        );

        $landingPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedLanding = $landingPackageElement->addChild('domain');
        $packagedLanding->adopt($landingXMLElement);

        $packagedIdentity = $landingPackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate landing module
         */
        $landingModule = new Component\Presenter('Module/Landing');
        $landingModuleXML = $landingModule->generate($landingPackageElement);

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $landingModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Landing Page"
        );
        $domainXMLElement->addChild('Landing', 'dsf');

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

}
