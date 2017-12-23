<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Website\Component\Presenter;

class User
{
    protected $recognitionAdapter;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter)
    {
        $this->recognitionAdapter = $recognitionAdapter;
    }

    public function getProfile($response)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement($identity->toXml());

        /**
         * Prepare modules
         */
        $userModule = new Presenter('Module/User');
        $userModuleXML = $userModule->generate($identityXMLElement);

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $userModuleXML);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Presenter('SinglePane');

        $response->setBody($presentation->generate($domainXMLElement));
    }

    public function getProtectedPage($response)
    {
        $response->setBody('ok');
    }
}
