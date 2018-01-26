<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;

class Quote
{
    protected $quoteLookup;
    protected $quoteUpload;

    public function __construct(
        Service\QuoteLookup $quoteLookup,
        Service\QuoteUpload $quoteManagement
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
