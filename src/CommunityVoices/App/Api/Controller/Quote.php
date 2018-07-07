<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Quote
{
    protected $quoteLookup;
    protected $quoteManagement;

    public function __construct(
        Service\QuoteLookup $quoteLookup,
        Service\QuoteManagement $quoteManagement,
        Service\TagLookup $tagLookup
    ){
        $this->quoteLookup = $quoteLookup;
        $this->quoteManagement = $quoteManagement;
        $this->tagLookup = $tagLookup;
    }

    /**
     * Quote lookup by id
     */
    public function getQuote($request)
    {
        $quoteId = $request->attributes->get('id');

        $this->quoteLookup->findById($quoteId);
    }

    public function getAllQuote($request, $identity)
    {

        $search = (string) $request->query->get('search');
        $tags = $request->query->get('tags');
        $attributions = $request->query->get('attributions');

        $creatorIDs = $request->attributes->get('creatorIDs');
        $status = $request->attributes->get('status');

        $status = ($status == Null) ? ["approved","pending","rejected"] : $status;
        if($identity->getRole() <= 2){
          $status = ["approved"];
        }

        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = 25; // number of items per page
        $offset = $limit * $page;

        $this->quoteLookup->findAll($page, $limit, $offset, $search, $tags, $attributions, $creatorIDs, $status);
    }

    public function getQuoteUpload()
    {
        $this->tagLookup->findAll();
    }

    public function postQuoteUpload($request, $identity)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $approved = $request->request->get('approved');
        $tags = $request->request->get('tags');
        
        if($identity->getRole() <= 2){
          $approved = null;
        }

        $this->quoteManagement->upload($text, $attribution, $subAttribution,
                        $dateRecorded, $approved,
                        $identity, $tags);
    }

    public function getQuoteUpdate($request)
    {
      $quoteId = $request->attributes->get('id');

      $this->quoteLookup->findById($quoteId);
    }

    public function postQuoteUpdate($request)
    {
      $text = $request->request->get('text');
      $attribution = $request->request->get('attribution');
      $subAttribution = $request->request->get('subAttribution');
      $dateRecorded = $request->request->get('dateRecorded');
      $status = $request->request->get('status');
      $id = $request->request->get('id');

      $this->quoteManagement->update($id, $text, $attribution, $subAttribution,
                                  $dateRecorded, $status);
    }
}
