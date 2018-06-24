<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Article
{
    protected $mapperFactory;

    public function __construct(MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function getArticle()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('articleLookup');
        $article = $stateObserver->getEntry('article')[0];

        $response = new HttpFoundation\JsonResponse($article->toArray());

        return $response;
    }

    public function getAllArticle()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('articleFindAll');
        $articleCollection = $stateObserver->getEntry('articleCollection')[0];

        $response = new HttpFoundation\JsonResponse($articleCollection->toArray());

        return $response;
    }

    public function getArticleUpload()
    {
        // intentionally blank
    }

    public function postArticleUpload()
    {
        // intentionally blank
    }

    public function getArticleUpdate()
    {
        // intentionally blank
    }

    public function postArticleUpdate()
    {
        // intentionally blank
    }
}
