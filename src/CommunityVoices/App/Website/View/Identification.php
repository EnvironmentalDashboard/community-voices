<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Identification extends Component\View
{
    protected $urlGenerator;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider,
        UrlGenerator $urlGenerator
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        $this->urlGenerator = $urlGenerator;
    }

    public function getLogin($request)
    {
        $referer = $request->request->get("referer") ?? $request->headers->get("referer");

        // If we are logged in, we should not be letting us log in again!
        if ($this->isLoggedIn($request)) {
            // Our goal is to either return from where we came or go back to
            // the root of the website.
            $response = new HttpFoundation\RedirectResponse(
                $referer ?? $this->urlGenerator->generate("root")
            );

            $this->finalize($response);
            return $response;
        }

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        /**
         * Grab cached form
         */
        $formCache = new Component\CachedItem('form');

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->fetch($formCache);

        $form = $formCache->getValue();

        $formParamXML = new Helper\SimpleXMLElementExtension('<form/>');
        $formParamXML->addAttribute('referer', $referer);
        $formParamXML->addAttribute('failure', !is_null($form));
        $formParamXML->addAttribute('email-value', $form['email']);
        $formParamXML->addAttribute('remember-value', $form['remember']);

        $formModule = new Component\Presenter('Module/Form/Login');
        $formModuleXML = $formModule->generate($formParamXML);

        $domainXMLElement->addChild('main-pane', $formModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Login"
        );
        $domainXMLElement->addChild('extraCSS', "register");

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    /**
     * User authenticaton
     */
    public function postCredentials($request, $result)
    {
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        if (!$result->errors) {
            setCookie("PHPSESSID", $result->sessionId);

            /**
             * Login success
             */
            $response = new HttpFoundation\RedirectResponse(
                $request->request->get("referer") ?? $this->urlGenerator->generate("root")
            );

            $this->finalize($response);
            return $response;
        } else {
            /**
             * Login failed; display login form
             */
            return $this->getLogin($request);
        }
    }

    public function getLogout($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer') ?? $this->urlGenerator->generate('root')
        );

        $this->finalize($response);
        return $response;
    }
}
