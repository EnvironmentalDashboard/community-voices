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
        $count = $obj->imageCollection->count;
        $limit = $obj->imageCollection->limit;
        $page = $obj->imageCollection->page;
        unset($obj->imageCollection->count); // TODO: fix!
        unset($obj->imageCollection->limit);
        unset($obj->imageCollection->page);
        $obj->imageCollection = (array) $obj->imageCollection;
        $imageXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        // Get all photographers for menu -- should this be done a different way?
        $photographers = $json->imageCollectionPhotographers;
        $photographerXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($photographers)
        );

        $orgs = $json->imageCollectionOrgs;
        $orgXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($orgs)
        );

        $tags = $json->tags;
        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($tags)
        );

        // TODO fix
        $pagination = new \stdClass();
        $pagination->div = $this->paginationHTML($count, $limit, $page);
        $paginationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($pagination)
        );

        /**
         * image XML Package
         */
        $imagePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedImage = $imagePackageElement->addChild('domain');
        $packagedImage->adopt($imageXMLElement);
        $packagedImage->adopt($photographerXMLElement);
        $packagedImage->adopt($orgXMLElement);
        $packagedImage->adopt($paginationXMLElement);
        $packagedImage->adopt($tagXMLElement);

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

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getImageUpload($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $imageAPIView = $this->secureContainer->contain($this->imageAPIView);

        $imageXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $imageAPIView->getImageUpload()->getContent()
            ))
        );

        $imagePackageElement = new Helper\SimpleXMLElementExtension('<package/>');
        $packagedImage = $imagePackageElement->addChild('domain');
        $packagedImage->adopt($imageXMLElement);
        $packagedIdentity = $imagePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);
        $imageModule = new Component\Presenter('Module/Form/ImageUpload');
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
        $domainXMLElement->addChild('title', "Community Voices: Image Upload");
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

    private function paginationHTML(int $count, int $limit, int $page) {
        parse_str($_SERVER['QUERY_STRING'], $qs);
        $final_page = ceil($count / $limit);
        $ret = '<nav aria-label="Page navigation example" class="text-center"><ul class="pagination" style="display: inline-flex;">';
        if ($page > 0) {
            $ret .= '<li class="page-item"><a class="page-link" href="images?';
            $ret .= http_build_query(array_replace($qs, ['page' => $page]));
            $ret .= '" aria-label="Previous"><span aria-hidden="true">&#171;</span><span class="sr-only">Previous</span></a></li>';
        }
        for ($i = 1; $i <= $final_page; $i++) {
            if ($page + 1 === $i) {
                $ret .= '<li class="page-item active"><a class="page-link" href="images?'. http_build_query(array_replace($qs, ['page' => $i])).'">' . $i . '</a></li>';
            }
            else {
                $ret .= '<li class="page-item"><a class="page-link" href="images?'. http_build_query(array_replace($qs, ['page' => $i])).'">' . $i . '</a></li>';
            }
        }
        if ($page + 1 < $final_page) {
            $ret .= '<li class="page-item"><a class="page-link" href="images?';
            $ret .= http_build_query(array_replace($qs, ['page' => $page+2]));
            $ret .= '" aria-label="Next"><span aria-hidden="true">&#187;</span><span class="sr-only">Next</span></a></li>';
        }
        $ret .= '</ul></nav>';
        return $ret;
    }
}
