<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Image extends Component\View
{
    protected $recognitionAdapter;
    protected $imageAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Image $imageAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->imageAPIView = $imageAPIView;
    }

    public function sendImage($routes, $context)
    {
        // wut
    }

    public function getImage($routes, $context)
    {
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather image information
         */
        $imageAPIView = $this->secureContainer->contain($this->imageAPIView);

        $imageXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $imageAPIView->getImage()->getContent()
            ))
        );

        /**
         * image XML Package
         */
        $imagePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedimage = $imagePackageElement->addChild('domain');
        $packagedimage->adopt($imageXMLElement);

        $packagedIdentity = $imagePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate image module
         */
        $imageModule = new Component\Presenter('Module/Image');
        $imageModuleXML = $imageModule->generate($imagePackageElement);

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $imageModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Image ".
            $imageXMLElement->id
        );
        $domainXMLElement->addChild('navbarSection', "image");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getAllImage($routes, $context)
    {
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather image information
         */
        $imageAPIView = $this->secureContainer->contain($this->imageAPIView);
        $json = json_decode($imageAPIView->getAllImage()->getContent());
        $obj = new \stdClass();
        $obj->imageCollection = $json->imageCollection;
        $imageXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        // Get all photographers for menu
        $photographers = $json->imageCollectionPhotographers;
        $photographerXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($photographers)
        );

        /**
         * image XML Package
         */
        $imagePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedImage = $imagePackageElement->addChild('domain');
        $packagedImage->adopt($imageXMLElement);
        $packagedImage->adopt($photographerXMLElement);

        $packagedIdentity = $imagePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        /**
         * Generate image module
         */
        $imageModule = new Component\Presenter('Module/ImageCollection');
        $imageModuleXML = $imageModule->generate($imagePackageElement);

        /**
         * Get base URL
         */
        $urlGenerator = new UrlGenerator($routes, $context);
        $baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $imageModuleXML);
        $domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', "Community Voices: All Images");
        $domainXMLElement->addChild('extraJS', 'images');
        // $domainXMLElement->addChild('navbarSection', "image");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getImageUpload($routes, $context)
    {
        try {
            $imageAPIView = $this->secureContainer->contain($this->imageAPIView);
            $imageAPIView->getImageUpload();
        } catch (Exception $e) {
            echo $e-getMessage();
            return;
        }
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Component\Presenter('Module/Form/ImageUpload');
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
            "Community Voices: Image Upload"
        );
        $domainXMLElement->addChild('navbarSection', "image");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postImageUpload($routes, $context)
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
        $domainXMLElement->addChild('navbarSection', "image");

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

    public function getImageUpdate($routes, $context)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        /**
         * Gather image information
         */
        $imageAPIView = $this->secureContainer->contain($this->imageAPIView);

        $imageXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $imageAPIView->getImage()->getContent()
            ))
        );

        $packagedImage = $paramXML->addChild('domain');
        $packagedImage->adopt($imageXMLElement);

        $formModule = new Component\Presenter('Module/Form/ImageUpdate');
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
            "Community Voices: Image Update"
        );
        $domainXMLElement->addChild('navbarSection', "image");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        // var_dump($domainIdentity);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postImageUpdate($routes, $context)
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
        $domainXMLElement->addChild('navbarSection', "image");

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
