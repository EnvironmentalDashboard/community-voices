<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Article extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer, $mapperFactory);
    }

    protected function getArticle()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('articleLookup');
        $article = $stateObserver->getEntry('article')[0];

        $response = new HttpFoundation\JsonResponse($article->toArray());

        return $response;
    }

    protected function getAllArticle()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('articleFindAll');
        $articleCollection = $stateObserver->getEntry('articleCollection')[0]->toArray();
        $articleCollection['authors'] = $stateObserver->getEntry('articleCollectionAuthors')[0];

        $stateObserver->setSubject('tagLookup');
        $articleCollection['tags'] = $stateObserver->getEntry('tag')[0]->toArray();

        $response = new HttpFoundation\JsonResponse($articleCollection);

        return $response;
    }

    protected function getArticleUpload()
    {
        // intentionally blank
    }

    protected function postArticleUpload()
    {
        // intentionally blank
    }

    protected function getArticleUpdate()
    {
        // intentionally blank
    }

    protected function postArticleUpdate()
    {
        // intentionally blank
    }

    protected function getArticleRelatedSlides()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('articleLookup');
        $slides = $stateObserver->getEntry('relatedSlides')[0];

        $response = new HttpFoundation\JsonResponse($slides);

        return $response;
    }
}
