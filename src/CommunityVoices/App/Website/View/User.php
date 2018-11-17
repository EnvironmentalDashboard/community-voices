<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class User extends Component\View
{
    protected $recognitionAdapter;
    protected $transcriber;

    public function __construct(Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber)
    {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
    }

    public function getProfile($request)
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

        /**
         * Prepare modules
         */
        $userModule = new Component\Presenter('Module/User');
        $userModuleXML = $userModule->generate($identityXMLElement);

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $userModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title',
            "Community Voices: ".
            $identityXMLElement->firstName.
            "'s Profile"
        );
        $domainXMLElement->addChild('extraJS', "user");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getProtectedPage($response)
    {
        $response = new HttpFoundation\Response('ok');

        $this->finalize($response);
        return $response;
    }

    public function getRegistration($request)
    {

        /* Gather identity information */
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        // $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $userPackageElement = new Helper\SimpleXMLElementExtension('<package/>');
        $packagedUser = $userPackageElement->addChild('domain');
        $packagedUser->adopt(new SimpleXMLElement(
            $this->transcriber->toXml(['token' => (isset($_GET['token'])) ? $_GET['token'] : ''])
        ));

        $packagedIdentity = $userPackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

        $userModule = new Component\Presenter('Module/Form/Register');
        $userModuleXML = $userModule->generate($userPackageElement);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $userModuleXML);

        $domainXMLElement->addChild(
            'title',
            "Community Voices: Register"
        );
        $domainXMLElement->addChild('extraJS', "register");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        // $domainXMLElement->addChild('main-pane', $formModuleXML);
        // //$domainXMLElement->addChild('baseUrl', $baseUrl);
        // $domainXMLElement->addChild('title',
        //     "Community Voices: Register"
        // );
        // $domainXMLElement->addChild('extraJS', "register");

        // /**
        //  * Prepare template
        //  */
        // $domainIdentity = $domainXMLElement->addChild('identity');
        // $domainIdentity->adopt($identityXMLElement);

        // $presentation = new Component\Presenter('SinglePane');

        // $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        // $this->finalize($response);
        // return $response;
    }

    public function postRegistration($request)
    {
        /**
         * @TODO This method isn't checking whether the registration is successful
         */
        
        return HttpFoundation\RedirectResponse(
            $this->urlGenerator->generate('root')
        );
    }

    public function postRegistrationInvite($request)
    {
        header('Location: /community-voices/');
        exit();
    }
}
