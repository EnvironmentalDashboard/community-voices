<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Website\Component\CachedItem;

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

        $formCache = new CachedItem('form');
        $formCache->setValue($form);

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->save($formCache);

        $this->recognitionAdapter->authenticate($email, $password, $remember);
    }

    public function getLogout($request)
    {
        $this->recognitionAdapter->logout();
    }
}
