<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class Article extends Component\View
{
    public function __construct(
        MapperFactory $mapperFactory
    ) {
        parent::__construct($mapperFactory);
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
        $articleCollection = $stateObserver->getEntry('articleCollection')[0]->toArray();
        $articleCollection['authors'] = $stateObserver->getEntry('articleCollectionAuthors')[0];

        $stateObserver->setSubject('tagLookup');
        $articleCollection['tags'] = $stateObserver->getEntry('tag')[0]->toArray();

        $response = new HttpFoundation\JsonResponse($articleCollection);

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
