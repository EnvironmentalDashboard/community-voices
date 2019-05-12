<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Identification
{
    protected $identificationAPIController;
    protected $mapperFactory;

    public function __construct(
        Api\Controller\Identification $identificationAPIController,
        Component\MapperFactory $mapperFactory
    ) {
        $this->identificationAPIController = $identificationAPIController;
        $this->mapperFactory = $mapperFactory;
    }

    public function getLogin($request)
    {
    }

    /**
     * User authentication
     */
    public function postCredentials($request)
    {
        $email    = $request->request->get('email');
        $password = $request->request->get('password');
        $remember = $request->request->get('remember') === 'on';

        $form = [
            'email' => $email,
            'remember' => $remember
        ];

        $formCache = new Component\CachedItem('form');
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);

        $this->identificationAPIController->postLogin($request);
    }

    public function getLogout($request)
    {
        $this->identificationAPIController->postLogout($request);
    }
}
