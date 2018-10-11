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
        $obj->slideCollection = (array) $json->slideCollection;
        unset($obj->slideCollection['count']);
        unset($obj->slideCollection['limit']);
        unset($obj->slideCollection['page']);
        foreach ($obj->slideCollection as $key => $slide) {
            $slide->slide->quote->quote->text = htmlspecialchars($slide->slide->quote->quote->text);
            $slide->slide->quote->quote->attribution = htmlspecialchars($slide->slide->quote->quote->attribution);
            $slide->slide->quote->quote->subAttribution = htmlspecialchars($slide->slide->quote->quote->subAttribution);
            $slide->slide->image->image->title = htmlspecialchars($slide->slide->image->image->title);
            $slide->slide->image->image->description = htmlspecialchars($slide->slide->image->image->description);
            $slide->slide->image->image->photographer = htmlspecialchars($slide->slide->image->image->photographer);
            $slide->slide->image->image->organization = htmlspecialchars($slide->slide->image->image->organization);
        }
        $obj->slideCollection = array_values($obj->slideCollection);

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
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $landingModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Landing Page"
        );
        $domainXMLElement->addChild('extraJS', 'landing');
        $domainXMLElement->addChild('metaDescription', "Community Voices communication technology combines images and words to advance environmental, social and economic sustainability in diverse communities.");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

}
