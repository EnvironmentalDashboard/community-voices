<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;

class ContentCategory extends Component\View
{
    //protected $contentCategoryAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider
        //Api\View\ContentCategory $contentCategoryAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        //$this->contentCategoryAPIView = $contentCategoryAPIView;
    }

    public function getAllContentCategory($request)
    {
        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson('/content-categories', $request)
            )
        );

        $contentCategoryPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedContentCategory = $contentCategoryPackageElement->addChild('domain');
        $packagedContentCategory->adopt($contentCategoryXMLElement);

        $packagedIdentity = $contentCategoryPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        $contentCategoryModule = new Component\Presenter('Module/ContentCategoryCollection');
        $contentCategoryModuleXML = $contentCategoryModule->generate($contentCategoryPackageElement);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $contentCategoryModuleXML);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getContentCategory($request)
    {
        $id = $request->attributes->get('groupId');
        $json = $this->apiProvider->getJson("/content-categories/{$id}", $request);

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($json)
        );

        $contentCategoryPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $height = (isset($_GET['height']) && intval($_GET['height']) > 0) ? (int) $_GET['height'] : 1080;
        $width = (isset($_GET['width']) && intval($_GET['width'])) > 0 ? (int) $_GET['width'] : 1920;
        $contentCategoryPackageElement->addChild('height', $height);
        $contentCategoryPackageElement->addChild('width', $width);

        $packagedContentCategory = $contentCategoryPackageElement->addChild('domain');
        $packagedContentCategory->adopt($contentCategoryXMLElement);

        $packagedIdentity = $contentCategoryPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        $contentCategoryModule = new Component\Presenter('Module/ContentCategory');
        $contentCategoryModuleXML = $contentCategoryModule->generate($contentCategoryPackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $contentCategoryModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Content Category " .
            $contentCategoryXMLElement->groupId
        );

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('Blank');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);

        return $response;
    }

    public function getContentCategoryUpload($request, $errors = self::ERRORS_DEFAULT)
    {
        return $this->getContentCategoryUpdate($request);
    }

    public function postContentCategoryUpload($request, $errors = self::ERRORS_DEFAULT)
    {
        if (!empty($errors->errors)) {
            return $this->getContentCategoryUpdate($request, $errors);
        }
        $response = new HttpFoundation\RedirectResponse(
            dirname($request->headers->get('referer'))
        );

        $this->finalize($response);
        return $response;
    }

    public function getContentCategoryUpdate($request, $errors = self::ERRORS_DEFAULT)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        try {
            $id = $request->attributes->get('groupId');
            $contentCategoryXMLElement = new SimpleXMLElement(
                $this->transcriber->toXml(
                    $this->apiProvider->getJson("/content-categories/{$id}", $request)
                )
            );

            $errorsXMLElement = new SimpleXMLElement(
                $this->transcriber->toXml($errors)
            );

            $packagedContentCategory = $paramXML->addChild('domain');
            $packagedContentCategory->adopt($contentCategoryXMLElement);
            $packagedContentCategory->adopt($errorsXMLElement);
        } catch (\Error $e) {
            // This happens when we are uploading, not updating.
            // Nothing is very big of a deal in this case.
        }

        $formModule = new Component\Presenter('Module/Form/ContentCategory');
        $formModuleXML = $formModule->generate($paramXML);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Content Category Update"
        );


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postContentCategoryUpdate($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            dirname(dirname($request->headers->get('referer')))
        );

        $this->finalize($response);
        return $response;
    }

    public function postContentCategoryDelete($request, $errors = self::ERRORS_DEFAULT)
    {
        if (!empty($errors->errors)) {
            return $this->getContentCategoryUpdate($request, $errors);
        }

        $response = new HttpFoundation\RedirectResponse(
            dirname(dirname($request->headers->get('referer')))
        );

        $this->finalize($response);
        return $response;
    }
}
