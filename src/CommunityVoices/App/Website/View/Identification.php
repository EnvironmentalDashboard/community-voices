<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Website\Component\CachedItem;
use CommunityVoices\App\Website\Component\Presenter;

class Identification
{
    protected $recognitionAdapter;
    protected $mapperFactory;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory)
    {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
    }

    public function getLogin($response)
    {
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Presenter('Module/Form/Login');
        $formModuleXML = $formModule->generate($paramXML);

        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement($identity->toXml());

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response->setBody($presentation->generate($domainXMLElement));
    }

    /**
     * User authenticaton
     */
    public function postCredentials($response)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement($identity->toXml());

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        if (!$identity->getId()) {
            /**
             * Login failed; display login form
             */

            // Grab cached form
            $formCache = new CachedItem('form');

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
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response->setBody($presentation->generate($domainXMLElement));
    }

    public function getLogout($response)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement($identity->toXml());

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Logged out.</p>');

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response->setBody($presentation->generate($domainXMLElement));
    }
}