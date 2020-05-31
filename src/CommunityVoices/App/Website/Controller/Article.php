<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\App\Website\Component;

class Article
{
    //protected $articleAPIController;
    protected $apiProvider;

    public function __construct(
        //Api\Controller\Article $articleAPIController
        Component\ApiProvider $apiProvider
    ) {
        //$this->articleAPIController = $articleAPIController;
        $this->apiProvider = $apiProvider;
    }

    public function getArticle($request)
    {
        //$this->articleAPIController->getArticle($request);
    }

    public function getAllArticle($request)
    {
        //$this->articleAPIController->getAllArticle($request);
    }

    public function getArticleUpload()
    {
        // intentionally blank
    }

    public function postArticleUpload($request)
    {
        //$this->articleAPIController->postArticleUpload($request);

        $errors = $this->apiProvider->postJson('/articles/new/authenticate', $request);
        return $errors;
    }

    public function getArticleUpdate($request)
    {
        //$this->articleAPIController->getArticle($request);
    }

    public function postArticleUpdate($request)
    {
        //$this->articleAPIController->postArticleUpdate($request);

        $id = $request->attributes->get('id');
        $errors = $this->apiProvider->postJson("/articles/{$id}/edit/authenticate", $request);
        return $errors;
    }
}
