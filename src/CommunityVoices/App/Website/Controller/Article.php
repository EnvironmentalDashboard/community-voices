<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;

class Article
{
    protected $recognitionAdapter;
    protected $articleAPIController;
    protected $secureContainer;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Api\Controller\Article $articleAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->articleAPIController = $articleAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getArticle($request)
    {
        $apiController = $this->secureContainer->contain($this->articleAPIController);

        $apiController->getArticle($request);
    }

    public function getAllArticle($request)
    {
        $apiController = $this->secureContainer->contain($this->articleAPIController);

        // [example] filter by creator IDs
        // $request->attributes->set('creatorIDs', [1, 3 ,4 ,5 ,6]);

        // [example] filter by status
        // $request->attributes->set('status', ['rejected', 'pending']);

        $identity = $this->recognitionAdapter->identify();

        $apiController->getAllArticle($request, $identity);
    }

    public function getArticleUpload()
    {
        // intentionally blank
    }

    public function postArticleUpload($request)
    {
        $apiController = $this->secureContainer->contain($this->articleAPIController);
        $identity = $this->recognitionAdapter->identify();

        $apiController->postArticleUpload($request, $identity);
    }

    public function getArticleUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->articleAPIController);

        $apiController->getArticle($request);
    }

    public function postArticleUpdate($request)
    {
        $apiController = $this->secureContainer->contain($this->articleAPIController);

        $apiController->postArticleUpdate($request);
    }
}
