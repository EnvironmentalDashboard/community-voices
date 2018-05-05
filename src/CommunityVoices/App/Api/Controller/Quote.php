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
        Service\QuoteManagement $quoteManagement
    ){
        $this->quoteLookup = $quoteLookup;
        $this->quoteManagement = $quoteManagement;
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
        $creatorIDs = $request->attributes->get('creatorIDs');
        $status = $request->attributes->get('status');

        $status = ($status == Null) ? ["approved","pending","rejected"] : $status;
        if($identity->getRole() <= 2){
          $status = ["approved"];
        }

        $this->quoteLookup->findAll($creatorIDs, $status);
    }

    public function getQuoteUpload()
    {
        // intentionally blank
    }

    public function postQuoteUpload($request, $identity)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $approved = $request->request->get('approved');
        
        if($identity->getRole() <= 2){
          $approved = null;
        }

        $this->quoteManagement->upload($text, $attribution, $subAttribution,
                        $dateRecorded, $approved,
                        $identity);
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
