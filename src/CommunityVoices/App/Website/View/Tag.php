<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;

class Tag extends Component\View
{
    //protected $tagAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider
        //Api\View\Tag $tagAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        //$this->tagAPIView = $tagAPIView;
    }

    public function getAllTag($request)
    {
        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson('/tags', $request)
            )
        );

        $tagPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedTag = $tagPackageElement->addChild('domain');
        $packagedTag->adopt($tagXMLElement);

        $packagedIdentity = $tagPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        $tagModule = new Component\Presenter('Module/TagCollection');
        $tagModuleXML = $tagModule->generate($tagPackageElement);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $tagModuleXML);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getTag($request)
    {
    }

    public function getTagUpload($request)
    {
    }

    public function postTagUpload($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
        );

        $this->finalize($response);
        return $response;
    }

    public function postTagDelete($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
        );

        $this->finalize($response);
        return $response;
    }
}
