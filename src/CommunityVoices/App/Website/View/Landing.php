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
    protected $landingAPIView;
    protected $contentCategoryAPIView;

    protected $contentCategoryAccessControl;
    protected $userAccessControl;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView,
        Api\View\Landing $landingAPIView,
        Api\View\ContentCategory $contentCategoryAPIView,
        Api\AccessControl\ContentCategory $contentCategoryAccessControl,
        Api\AccessControl\User $userAccessControl
    ) {
        parent::__construct($mapperFactory, $transcriber, $identificationAPIView);

        $this->landingAPIView = $landingAPIView;
        $this->contentCategoryAPIView = $contentCategoryAPIView;

        $this->contentCategoryAccessControl = $contentCategoryAccessControl;
        $this->userAccessControl = $userAccessControl;
    }

    public function getLanding($request)
    {
        /**
         * Gather landing information
         */
        $json = json_decode($this->landingAPIView->getLanding()->getContent());
        $obj = new \stdClass;
        $obj->slideCollection = (array) $json->slideCollection;
        unset($obj->slideCollection['count']);
        unset($obj->slideCollection['limit']);
        unset($obj->slideCollection['page']);
        foreach ($obj->slideCollection as $key => $slide) {
            $slide->slide->quote->quote->text = $slide->slide->quote->quote->text;
            $slide->slide->quote->quote->attribution = $slide->slide->quote->quote->attribution;
            $slide->slide->quote->quote->subAttribution = $slide->slide->quote->quote->subAttribution;
            $slide->slide->image->image->title = $slide->slide->image->image->title;
            $slide->slide->image->image->description = $slide->slide->image->image->description;
            $slide->slide->image->image->photographer = $slide->slide->image->image->photographer;
            $slide->slide->image->image->organization = $slide->slide->image->image->organization;
        }
        $obj->slideCollection = array_values($obj->slideCollection);

        $landingXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->contentCategoryAPIView->getAllContentCategory()->getContent()
            ))
        );

        $landingPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedLanding = $landingPackageElement->addChild('domain');
        $packagedLanding->adopt($landingXMLElement);
        $packagedLanding->adopt($contentCategoryXMLElement);

        $packagedIdentity = $landingPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement());

        // needs to actually use objects - therefore, needs design first
        $packagedAccessControl = $landingPackageElement->addChild('accessControl');
        $this->addAccessControlRule($packagedAccessControl, 'ContentCategory', 'getAllContentCategoryFromNavbar', $this->contentCategoryAccessControl->getAllContentCategoryFromNavbar());
        $this->addAccessControlRule($packagedAccessControl, 'User', 'getAllUser', $this->userAccessControl->getAllUser());

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
        $domainXMLElement->addChild('extraCSS', 'landing');
        $domainXMLElement->addChild('extraJS', 'landing');
        $domainXMLElement->addChild('metaDescription', "Community Voices communication technology combines images and words to advance environmental, social and economic sustainability in diverse communities.");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement());

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
