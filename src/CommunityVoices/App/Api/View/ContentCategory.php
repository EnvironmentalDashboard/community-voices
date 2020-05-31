<?php

namespace CommunityVoices\App\Api\View;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\HttpFoundation\Response;

class ContentCategory extends Component\View
{
    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory
    ) {
        parent::__construct($secureContainer, $mapperFactory);
    }

    protected function getAllContentCategory()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('contentCategoryLookup');
        $contentCategories = $stateObserver->getEntry('contentCategory')[0];

        $response = new HttpFoundation\JsonResponse($contentCategories->toArray());

        return $response;
    }

    protected function getContentCategory()
    {
        $clientState = $this->mapperFactory->createClientStateMapper();
        $stateObserver = $clientState->retrieve();

        $stateObserver->setSubject('contentCategoryLookup');
        $contentCategory = $stateObserver->getEntry('contentCategory')[0];

        $response = new HttpFoundation\JsonResponse($contentCategory->toArray());

        return $response;
    }

    protected function getContentCategoryUpload()
    {
        // intentionally blank
    }

    protected function postContentCategoryUpload($request)
    {
        // Should return errorsResponse like an API, but this makes file upload work.
        $response = new HttpFoundation\RedirectResponse(
            dirname($request->headers->get('referer'))
        );

        return $response;
    }

    protected function getContentCategoryUpdate()
    {
        // intentionally blank
    }

    protected function postContentCategoryUpdate($request)
    {
        // Should return errorsResponse like an API, but this makes file upload work.
        $response = new HttpFoundation\RedirectResponse(
            dirname(dirname($request->headers->get('referer')))
        );

        return $response;
    }

    protected function postContentCategoryDelete()
    {
        // intentionally blank
    }
}
