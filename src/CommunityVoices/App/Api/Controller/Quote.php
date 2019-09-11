<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Api\Component;

class Quote extends Component\Controller
{
    protected $recognitionAdapter;
    protected $quoteLookup;
    protected $quoteManagement;

    // for future usage of this pattern: the value is the default value
    const FORM_ATTRIBUTES = [
        'text',
        'originalText',
        'interviewer',
        'attribution',
        'subAttribution',
        'quotationMarks' => false,
        'dateRecorded',
        'status' => Entity\Media::STATUS_PENDING,
        'tags' => [],
        'contentCategories' => []
    ];

    public function __construct(
        Component\Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,
        \Auryn\Injector $injector,

        Component\RecognitionAdapter $recognitionAdapter,
        Service\QuoteLookup $quoteLookup,
        Service\QuoteManagement $quoteManagement
    ) {
        parent::__construct($identifier, $logger, $injector);

        $this->recognitionAdapter = $recognitionAdapter;
        $this->quoteLookup = $quoteLookup;
        $this->quoteManagement = $quoteManagement;
    }

    /**
     * Quote lookup by id
     */
    protected function getQuote($request)
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
    protected function getBoundaryQuotes($request)
    {
        $quoteId = (int) $request->attributes->get('id');

        $this->quoteLookup->findBoundaryQuotesById($quoteId);
    }

    protected function getAllQuote($request)
    {
        $identity = $this->recognitionAdapter->identify();

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

    protected function getQuoteUpload()
    {
        // intentionally blank
    }

    protected function postQuoteUpload($request)
    {
        $identity = $this->recognitionAdapter->identify();

        // this needs to be in QuoteManagement->save,
        // but can be saved for when user roles are worked on
        // if ($identity->getRole() <= 2) {
        //     $approved = null;
        // }

        return $this->quoteManagement->save(
            null,
            $this->getFormAttributes($request, self::FORM_ATTRIBUTES),
            $identity
        );
    }

    protected function getQuoteUpdate($request)
    {
        // In order to autofill some form values,
        // we need to get the current quote's data.
        $this->getQuote($request);
    }

    protected function postQuoteUpdate($request)
    {
        return $this->quoteManagement->save(
            $this->getId($request),
            $this->getFormAttributes($request, self::FORM_ATTRIBUTES)
        );
    }

    protected function postQuoteDelete($request)
    {
        $id = (int) $request->attributes->get('id');

        $this->quoteManagement->delete($id);
    }

    protected function postQuoteUnpair($request)
    {
        $quote_id = (int) $request->attributes->get('quote');
        $slide_id = (int) $request->attributes->get('slide');

        $this->quoteManagement->unpair($quote_id, $slide_id);
    }
}
