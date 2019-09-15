<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class DisplayError extends Component\View
{
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView,
        Api\AccessControl\ContentCategory $contentCategoryAccessControl,
        Api\AccessControl\User $userAccessControl
    ) {
        parent::__construct($mapperFactory, $transcriber, $identificationAPIView, $contentCategoryAccessControl, $userAccessControl);
    }

    public function getError($request)
    {
        /**
         * DisplayError XML Package
         */
        $displayErrorPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        // Check if we are receiving a 404 error so that we can display the 404 page.
        // Also check for an access denied error to display the access denied page.
        $error = $request->attributes->get("error");
        if ($error === "Symfony\\Component\\Routing\\Exception\\ResourceNotFoundException") {
            $displayErrorPackageElement->addAttribute("error", "404");
        } else if ($error === "CommunityVoices\\App\\Api\\Component\\Exception\\AccessDenied") {
            $displayErrorPackageElement->addAttribute("error", "AccessDenied");
        }

        $identity = $displayErrorPackageElement->addChild('identity');
        $identity->adopt($this->identityXMLElement());

        /**
         * Generate DisplayError module
         */
        $displayErrorModule = new Component\Presenter('Module/DisplayError');
        $displayErrorModuleXML = $displayErrorModule->generate($displayErrorPackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $displayErrorModuleXML);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
