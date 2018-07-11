<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Slide extends Component\View
{
    protected $recognitionAdapter;
    protected $slideAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Slide $slideAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->slideAPIView = $slideAPIView;
    }

    public function getAllSlide($routes, $context)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather slide information
         */
        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        // var_dump($slideAPIView->getAllSlide()->getContent());die;
        $json = json_decode($slideAPIView->getAllSlide()->getContent());
        // var_dump($json->slideCollection);die;
        $obj = new \stdClass();
        $obj->slideCollection = (array) $json->slideCollection;
        $count = $obj->slideCollection['count'];
        $limit = $obj->slideCollection['limit'];
        $page = $obj->slideCollection['page'];
        unset($obj->slideCollection['count']);
        unset($obj->slideCollection['limit']);
        unset($obj->slideCollection['page']);
        foreach ($obj->slideCollection as $key => $slide) {
            $slide->slide->g = htmlspecialchars($slide->slide->g);
            $slide->slide->quote->quote->text = htmlspecialchars($slide->slide->quote->quote->text);
            $slide->slide->quote->quote->attribution = htmlspecialchars($slide->slide->quote->quote->attribution);
            $slide->slide->quote->quote->subAttribution = htmlspecialchars($slide->slide->quote->quote->subAttribution);
            // $fn = $slide->slide->image->image->filename;
            // $slide->slide->image->image->SvgImagePos = (file_exists($fn) && getimagesize($fn)[1] > 400) ? 3 : 10;
            // var_dump($slide->slide->SvgImagePos);die;
        }
        $obj->slideCollection = array_values($obj->slideCollection);
        // $obj->slideCollection = $slideCollection;
// var_dump(($obj->slideCollection));exit;
        $slideXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($obj)
        );
// var_dump($slideXMLElement);die;
        $pagination = new \stdClass();
        $pagination->div = $this->paginationHTML($qs, $count, $limit, $page);
        $paginationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($pagination)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($paginationXMLElement);

        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate slide module
         */
        // var_dump($slidePackageElement->domain->slideCollection->slide->tspan->asXML());exit;
        $slideModule = new Component\Presenter('Module/SlideCollection');
        $slideModuleXML = $slideModule->generate($slidePackageElement);

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: All Slides".
            $slideXMLElement->id
        );
        $domainXMLElement->addChild('navbarSection', "slide");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getSlide($routes, $context)
    {
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather slide information
         */
        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        $json = json_decode($slideAPIView->getSlide()->getContent());

        $json->slide->g = htmlspecialchars($json->slide->g);

        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($json)
        );

        /**
         * Slide XML Package
         */
        $slidePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);

        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate slide module
         */
        $slideModule = new Component\Presenter('Module/Slide');
        $slideModuleXML = $slideModule->generate($slidePackageElement);

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Slide ".
            $slideXMLElement->id
        );
        $domainXMLElement->addChild('navbarSection', "slide");
        $domainXMLElement->addChild('comfortaa', "1");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getSlideUpload($routes, $context)
    {
        $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
        $json = json_decode($slideAPIView->getSlideUpload()->getContent());

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $obj = new \stdClass;
        $obj->groupCollection = $json->groupCollection;
        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $obj2 = new \stdClass;
        $obj2->attributionCollection = $json->attributionCollection;
        $attrXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj2)
        );        

        $paramXML = new SimpleXMLElement('<form/>');
        $formModule = new Component\Presenter('Module/Form/SlideUpload');
        $formModuleXML = $formModule->generate($paramXML);
        // var_dump($formModuleXML);die;

        $slidePackageElement = new Helper\SimpleXMLElementExtension('<form/>');
        $packagedSlide = $slidePackageElement->addChild('domain');
        $packagedSlide->adopt($slideXMLElement);
        $packagedSlide->adopt($attrXMLElement);
        $packagedIdentity = $slidePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);
        $slideModule = new Component\Presenter('Module/Form/SlideUpload');
        $slideModuleXML = $slideModule->generate($slidePackageElement);
        // var_dump($slideModuleXML);die;

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        //
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');
        $domainXMLElement->addChild('main-pane', $slideModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Slide Upload"
        );
        $domainXMLElement->addChild('extraJS', "create-slide");
        $domainXMLElement->addChild('comfortaa', "1");


        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postSlideUpload($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        $domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainXMLElement->addChild(
          'title',
          "Community Voices"
        );

        /**
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

}
