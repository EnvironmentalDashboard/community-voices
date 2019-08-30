<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class User extends Component\View
{
    protected $urlGenerator;
    protected $userAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView,
        UrlGenerator $urlGenerator,
        Api\View\User $userAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $identificationAPIView);

        $this->urlGenerator = $urlGenerator;
        $this->userAPIView = $userAPIView;
    }

    public function getUser($request)
    {
        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        // User data gathering
        $userXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->userAPIView->getUser()->getContent()
            ))
        );

        $rolesXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml([
                'roles' =>
                    array_map(function ($value) {
                        return [
                            'role' => [
                                'name' => ucfirst($value),
                                'value' => Entity\User::STRING_TO_ROLE[$value]
                            ]
                        ];
                    }, Entity\User::ALLOWABLE_DATABASE_ROLE)
            ])
        );

        /**
         * User XML Package
         */
        $userPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedUser = $userPackageElement->addChild('domain');
        $packagedUser->adopt($userXMLElement);
        $packagedUser->adopt($rolesXMLElement);

        $packagedIdentity = $userPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement());

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
        $domainIdentity->adopt($this->identityXMLElement());

        $domainXMLElement->addChild('extraJS', "user");

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    // This needs to be patterned VERY badly.
    public function getAllUser($request)
    {
        $userXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->userAPIView->getAllUser()->getContent()
            ))
        );

        // This allows us to loop over the existing roles in the database.
        $rolesXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml([
                'roles' =>
                    array_map(function ($value) {
                        return [
                            'role' => [
                                // Sad that XSLT doesn't easily capitalize.
                                'capitalized' => ucfirst($value),
                                'lowercase' => $value
                            ]
                        ];
                    }, Entity\User::ALLOWABLE_DATABASE_ROLE)
            ])
        );

        $userPackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedUser = $userPackageElement->addChild('domain');
        $packagedUser->adopt($userXMLElement);
        $packagedUser->adopt($rolesXMLElement);

        $packagedIdentity = $userPackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement());

        $userModule = new Component\Presenter('Module/UserCollection');
        $userModuleXML = $userModule->generate($userPackageElement);

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $userModuleXML);

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement());

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
        if ($this->isLoggedIn()) {
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
        $userXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->userAPIView->postRegistration()->getContent()
            ))
        );

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
        $errors = $this->userAPIView->postRegistration()->getContent();

        if (!empty($errors)) {
            return $this->getRegistration($request);
        }

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
