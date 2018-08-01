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
        Service\SlideManagement $slideManagement,
        Service\TagLookup $tagLookup,
        Service\QuoteLookup $quoteLookup,
        Service\ImageLookup $imageLookup
    ) {
        $this->slideLookup = $slideLookup;
        $this->slideManagement = $slideManagement;
        $this->tagLookup = $tagLookup;
        $this->quoteLookup = $quoteLookup;
        $this->imageLookup = $imageLookup;
    }

    /**
     * Grabs all slides from databbase
     * @param  Request $request A request from the client's machine
     * @return SlideCollection  A collection of all slides in the database
     */
    public function getAllSlide($request)
    {
        $search = (string) $request->query->get('search');
        $tags = $request->query->get('tags');
        $photographers = $request->query->get('photographers');
        $orgs = $request->query->get('orgs');
        $order = (string) $request->query->get('order');
        $attributions = $request->query->get('attributions');

        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = (int) $request->query->get('per_page');
        if ($limit < 1) {
            $limit = 12; // number of items per page
        }
        $offset = $limit * $page;
        $cc = (is_array($request->query->get('content_category'))) ? $request->query->get('content_category') : [];
        $stateObserver = $this->tagLookup->findAll(true);
        $stateObserver = $this->slideLookup->findAll($page, $limit, $offset, $order, $search, $tags, $photographers, $orgs, $attributions, $cc, $stateObserver);
        $stateObserver = $this->quoteLookup->attributions($stateObserver, true);
        $stateObserver = $this->imageLookup->photographers($stateObserver, true);
        $this->imageLookup->orgs($stateObserver);
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
        $stateObserver = $this->tagLookup->findAll(true);
        $stateObserver = $this->imageLookup->photographers($stateObserver, true);
        $stateObserver = $this->imageLookup->orgs($stateObserver, true);
        $this->quoteLookup->attributions($stateObserver);

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

    public function getSlideUpdate($request)
    {
        $slideId = $request->attributes->get('id');

        $stateObserver = $this->tagLookup->findAll(true);
        $stateObserver = $this->imageLookup->photographers($stateObserver, true);
        $stateObserver = $this->imageLookup->orgs($stateObserver, true);
        $stateObserver = $this->quoteLookup->attributions($stateObserver, true);
        $this->slideLookup->findById($slideId, $stateObserver);
    }

    public function postSlideUpdate($request)
    {
        $imageId = (int) $request->request->get('image_id');
        $quoteId = (int) $request->request->get('quote_id');
        $contentCategory = (int) $request->request->get('content_category');
        $decay_percent = (int) $request->request->get('decay_percent');
        $probability = (float) $request->request->get('probability');
        $decay_start = (string) $request->request->get('decay_start');
        $decay_end = (string) $request->request->get('decay_end');
        $id = (int) $request->request->get('id');

        $this->slideManagement->update($id, $imageId, $quoteId, $contentCategory, $decay_percent, $probability, $decay_start, $decay_end);
    }

}
