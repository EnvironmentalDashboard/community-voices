<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class User extends Component\View
{
    protected $urlGenerator;
    // protected $userAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider,
        UrlGenerator $urlGenerator
        // Api\View\User $userAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        $this->urlGenerator = $urlGenerator;
        // $this->userAPIView = $userAPIView;
    }

    public function getUser($request)
    {
        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        // User data gathering
        $id = $request->attributes->get('id');
        $userXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/user/{$id}", $request)
            )
        );

        /**
         * User XML Package
         */
        $userPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedlocation = $userPackageElement->addChild('domain');
        $packagedlocation->adopt($userXMLElement);

        $packagedIdentity = $userPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        /**
         * Generate User module
         */
        $userModule = new Component\Presenter('Module/User');
        $userModuleXML = $userModule->generate($userPackageElement);

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $userModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $domainXMLElement->addChild('extraJS', "user");

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
        // If we are already logged in, there are two cases:
        // 1. We logged in, then clicked on register.
        // 2. We just successfully registered.
        // In both cases, we want to leave this registration page.
        if ($this->isLoggedIn($request)) {
            $response = new HttpFoundation\RedirectResponse(
                $this->urlGenerator->generate('root')
            );

            $this->finalize($response);
            return $response;
        }

        /**
         * Grab cached form
         */
        $formCache = new Component\CachedItem('registrationForm');

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->fetch($formCache);

        $form = $formCache->getValue();

        /**
         * Construct form
         */
        $formParamXML = new Helper\SimpleXMLElementExtension('<form/>');
        $formParamXML->addAttribute('email-value', $form['email']);
        $formParamXML->addAttribute('firstName-value', $form['firstName']);
        $formParamXML->addAttribute('lastName-value', $form['lastName']);
        $formParamXML->addAttribute('token-value', $form['token']);

        // User data gathering
        // pass this in
        // $userXMLElement = new SimpleXMLElement(
        //     $this->transcriber->toXml(
        //         $this->userAPIView->postUser()->getContent()
        //     )
        // );

        $packagedUser = $formParamXML->addChild('domain');
        $packagedUser->adopt($userXMLElement);

        $formModule = new Component\Presenter('Module/Form/Register');
        $formModuleXML = $formModule->generate($formParamXML);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);

        $domainXMLElement->addChild(
            'title',
            "Community Voices: Register"
        );
        $domainXMLElement->addChild('extraJS', "register");

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postRegistration($request)
    {
        // Pass this in
        // $errors = $this->userAPIView->postUser()->getContent();
        //
        // if (!empty($errors)) {
        //     return $this->getRegistration($request);
        // }

        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
        );

        $this->finalize($response);
        return $response;
    }

    public function postRegistrationInvite($request)
    {
        header('Location: /community-voices/');
        exit();
    }
}
