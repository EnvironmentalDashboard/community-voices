<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;

class Quote
{
    protected $quoteLookup;
    protected $quoteUpload;

    public function __construct(
        Service\QuoteLookup $quoteLookup,
        Service\QuoteUpload $quoteUpload
    ){
        $this->quoteLookup = $quoteLookup;
        $this->quoteUpload = $quoteUpload;
    }

    /**
     * Quote lookup by id
     */
    public function getQuote($request)
    {
        $quoteId = $request->attributes->get('id');

        $this->quoteLookup->findById($quoteId);
    }

    public function postQuote($request)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('attribution');
        $dateRecorded = $request->request->get('attribution');
        $publicDocumentLink = $request->request->get('attribution');
        $sourceDocumentLink = $request->request->get('attribution');

        $this->$quoteUpload->newQuote($text, $attribution, $subAttribution,
                        $dateRecorded, $publicDocumentLink, $sourceDocumentLink);
    }
}
