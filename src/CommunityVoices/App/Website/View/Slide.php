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
        $obj->slideCollection = $json->slideCollection;
        $count = $obj->slideCollection->count;
        $limit = $obj->slideCollection->limit;
        $page = $obj->slideCollection->page;
        unset($obj->slideCollection->count);
        unset($obj->slideCollection->limit);
        unset($obj->slideCollection->page);
        $obj->slideCollection = array_values((array) $obj->slideCollection);
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
        // var_dump($slidePackageElement->domain->slideCollection->slide->quote->quote->text->asXML());exit;
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

        $slideXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $slideAPIView->getSlide()->getContent()
            ))
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

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getSlideUpload($routes, $context)
    {
        try {
            $slideAPIView = $this->secureContainer->contain($this->slideAPIView);
            $slideAPIView->getSlideUpload();
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Component\Presenter('Module/Form/SlideUpload');
        $formModuleXML = $formModule->generate($paramXML);

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        //

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Slide Upload"
        );
        $domainXMLElement->addChild('navbarSection', "slide");
        $domainXMLElement->addChild('extraJS', "create-slide");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

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
