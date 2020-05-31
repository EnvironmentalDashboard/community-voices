<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Identification
{
    protected $identificationAPIController;
    protected $mapperFactory;
    protected $apiProvider;

    public function __construct(
        Api\Controller\Identification $identificationAPIController,
        Component\MapperFactory $mapperFactory,
        Component\ApiProvider $apiProvider
    ) {
        $this->identificationAPIController = $identificationAPIController;
        $this->mapperFactory = $mapperFactory;
        $this->apiProvider = $apiProvider;
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

        // Post to API here.
        $this->apiProvider->post('/login', $request);
    }

    public function getLogout($request)
    {
        $this->apiProvider->get('/logout', $request);
    }
}
