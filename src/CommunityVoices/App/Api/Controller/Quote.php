<?php

namespace CommunityVoices\App\Api\Controller;

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

    public function getAllQuote($request)
    {
        $this->quoteLookup->findAll();
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

      $this->quoteManagement->update($text, $attribution, $subAttribution,
                                  $dateRecorded, $status);
    }
}
