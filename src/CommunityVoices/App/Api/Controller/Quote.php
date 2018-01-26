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

    public function postQuote($request, $identity)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $publicDocumentLink = $request->request->get('$publicDocumentLink');
        $sourceDocumentLink = $request->request->get('$sourceDocumentLink');

        $this->quoteManagement->upload($text, $attribution, $subAttribution,
                        $dateRecorded, $publicDocumentLink, $sourceDocumentLink,
                        $identity);
    }
}
