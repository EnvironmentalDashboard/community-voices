<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;

class Quote
{
    protected $quoteLookup;

    public function __construct(Service\QuoteLookup $quoteLookup)
    {
        $this->quoteLookup = $quoteLookup;
    }

    /**
     * Quote lookup by id
     */
    public function getQuote($request)
    {
        $quoteId = $request->attributes->get('id');

        $this->quoteLookup->findById($quoteId);
    }
}
