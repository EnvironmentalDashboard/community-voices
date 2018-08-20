<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Website\Component\Presenter;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Identification extends Component\View
{
    protected $recognitionAdapter;
    protected $mapperFactory;
    protected $transcriber;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber)
    {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
    }

    public function getLogin($routes, $context)
    {
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Presenter('Module/Form/Login');
        $formModuleXML = $formModule->generate($paramXML);

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', 
            "Community Voices: Login"
        );
        $domainXMLElement->addChild('extraCSS', "register");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    /**
     * User authenticaton
     */
    public function postCredentials($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        if (!$identity->getId()) {
            /**
             * Login failed; display login form
             */

            // Grab cached form
            $formCache = new Component\CachedItem('form');

            $cacheMapper = $this->mapperFactory->createCacheMapper();
            $cacheMapper->fetch($formCache);

            $form = $formCache->getValue();

            // Construct form
            $formParamXML = new SimpleXMLElement('<form/>');
            $formParamXML->addAttribute('failure', true);
            $formParamXML->addAttribute('email-value', $form['email']);
            $formParamXML->addAttribute('remember-value', $form['remember']);

            $formModule = new Presenter('Module/Form/Login');
            $formModuleXML = $formModule->generate($formParamXML);

            $domainXMLElement->addChild('main-pane', $formModuleXML);
        } else {
            /**
             * Login success; success message (maybe redirect)
             */

            $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        }

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', 
            "Community Voices: Welcome"
        );

        /**
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getLogout($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Logged out.</p>');
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', 
            "Community Voices: Logout"
        );

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
