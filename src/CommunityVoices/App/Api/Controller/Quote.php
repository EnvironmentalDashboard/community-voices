<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Api\Component;

class Quote extends Component\Controller
{
    protected $quoteLookup;
    protected $quoteManagement;

    public function __construct(
        Service\QuoteLookup $quoteLookup,
        Service\QuoteManagement $quoteManagement
    ) {
        $this->quoteLookup = $quoteLookup;
        $this->quoteManagement = $quoteManagement;
    }

    /**
     * Quote lookup by id
     */
    public function getQuote($request)
    {
        $quoteId = (int) $request->attributes->get('id');

        try {
            $this->quoteLookup->findById($quoteId);
        } catch (Exception\IdentityNotFound $e) {
            /**
           * @todo This is not necessarily the way to handle 404s
           */
            $this->send404();
        }
    }

    /**
     * Look up quotes that boundary queried quote
     */
    public function getBoundaryQuotes($request)
    {
        $quoteId = (int) $request->attributes->get('id');

        $this->quoteLookup->findBoundaryQuotesById($quoteId);
    }

    public function getAllQuote($request, $identity)
    {
        /**
         * Grab parameters from bag
         */

        $search = $request->query->get('search');
        $tags = $request->query->get('tags');
        $contentCategories = $request->query->get('contentCategories');
        $attributions = $request->query->get('attributions');
        $subattributions = $request->query->get('subattributions');
        $creatorIDs = $request->attributes->get('creatorIDs');
        $status = $request->query->get('status');
        $order = (string) $request->query->get('order');
        $page = (int) $request->query->get('page');
        $limit = (int) $request->query->get('per_page');
        $only_unused = (int) $request->query->get('unused');

        /**
         * Pre-process parameters
         */
        $status = ($status == null) ? ["approved", "pending", "rejected"] : explode(',', $status);

        /**
         * @todo
         *
         * RBAC usually be in the ACL, not here (i.e., two different View-
         * Controller pairs, arbitrated by a secure container)
         */
        if ($identity->getRole() <= 2) {
            $status = ["approved"];
        }

        /**
         * Current page, indexed at zero
         * @var int
         */
        $page = ($page > 0) ? $page - 1 : 0;

        /**
         * Items per page
         * @var int
         */
        $limit = ($limit > 0) ? $limit : 25; // number of items per page
        $offset = $limit * $page;

        $only_unused = !!$only_unused;

        $this->quoteLookup->findAll($page, $limit, $offset, $order, $only_unused, $search, $tags, $contentCategories, $attributions, $subattributions, $creatorIDs, $status);
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
        $tags = $request->request->get('tags');
        $contentCategories = $request->request->get('contentCategories');

        if ($identity->getRole() <= 2) {
            $approved = null;
        }

        $this->quoteManagement->upload(
            $text,
            $attribution,
            $subAttribution,
            $dateRecorded,
            $approved,
            $identity,
            $tags,
            $contentCategories
        );
    }

    public function getQuoteUpdate($request)
    {
        // In order to autofill some form values,
        // we need to get the current quote's data.
        $this->getQuote($request);
    }

    public function postQuoteUpdate($request)
    {
        $text = $request->request->get('text');
        $attribution = $request->request->get('attribution');
        $subAttribution = $request->request->get('subAttribution');
        $dateRecorded = $request->request->get('dateRecorded');
        $status = $request->request->get('status');
        $tags = $request->request->get('tags') ?? [];
        $contentCategories = $request->request->get('contentCategories') ?? [];

        $id = (int) $request->attributes->get('id');
        if ($id === 0) {
            $id = (int) $request->request->get('id');
        }

        return $this->quoteManagement->update(
            $id,
            $text,
            $attribution,
            $subAttribution,
            $dateRecorded,
            $status,
            $tags,
            $contentCategories
        );
    }

    public function postQuoteDelete($request)
    {
        $id = (int) $request->attributes->get('id');

        $this->quoteManagement->delete($id);
    }

    public function postQuoteUnpair($request)
    {
        $quote_id = (int) $request->attributes->get('quote');
        $slide_id = (int) $request->attributes->get('slide');

        $this->quoteManagement->unpair($quote_id, $slide_id);
    }
}
