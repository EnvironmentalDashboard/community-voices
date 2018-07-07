<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Slide
{
    protected $slideLookup;
    protected $slideManagement;

    public function __construct(
        Service\SlideLookup $slideLookup,
        Service\SlideManagement $slideManagement
    ) {
        $this->slideLookup = $slideLookup;
        $this->slideManagement = $slideManagement;
    }

    /**
     * Grabs all slides from databbase
     * @param  Request $request A request from the client's machine
     * @return SlideCollection  A collection of all slides in the database
     */
    public function getAllSlide($request)
    {
        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = 25; // number of items per page
        $offset = $limit * $page;
        $this->slideLookup->findAll($page, $limit, $offset);
    }

    /**
     * Slide lookup by id
     */
    public function getSlide($request)
    {
        $slideId = $request->attributes->get('id');

        $this->slideLookup->findById($slideId);
    }

    public function getSlideUpload()
    {
        // intentionally blank
    }

    public function postSlideUpload($request, $identity)
    {
        $imageId = $request->request->get('image_id');
        $quoteId = $request->request->get('quote_id');
        $contentCategory = $request->request->get('content_category');
        $dateRecorded = 'now';
        $approved = null;//$request->request->get('approved');

        // if ($identity->getRole() <= 2){
        //   $approved = null;
        // }

        $this->slideManagement->upload($quoteId, $imageId, $contentCategory, $dateRecorded, $approved, $identity);
    }

}
