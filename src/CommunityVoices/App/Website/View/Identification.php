<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Identification extends Component\View
{
    protected $recognitionAdapter;
    protected $mapperFactory;
    protected $transcriber;
    protected $urlGenerator;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        UrlGenerator $urlGenerator
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->urlGenerator = $urlGenerator;
    }

    public function getLogin($request)
    {
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        // If we are logged in, we should not be letting us log in again!
        if ($identity->getId()) {
            $response = new HttpFoundation\RedirectResponse(
                $this->urlGenerator->generate('root')
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
    public function postCredentials($request)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        if ($identity->getId()) {
            /**
             * Login success
             */

            $response = new HttpFoundation\RedirectResponse(
                $this->urlGenerator->generate('root')
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
