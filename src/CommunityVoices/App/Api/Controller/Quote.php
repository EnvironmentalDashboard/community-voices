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

    const ERR_NO_ATTRIBUTIONS = 'The source table must provide an attribution column';
    const ERR_NO_CONTENT_CATEGORIES = 'The quotes table must provide a content category column';
    const ERR_NO_IDENTIFIER = 'You are missing an identifier!';
    const ERR_MISSING_ATTRIBUTION = 'Quotes must have an attribution.';
    const ERR_MISSING_CONTENT_CATEGORY = 'Must provide a potential content category.';
    const ERR_MISSING_IDENTIFIER = 'This identifier is empty';
    const WARNING_EMPTY_QUOTE = "Warning! You have empty quotes. Do you want to procede?";
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
    const BATCH_QUOTE_DATA = [
        'identifier',
        'originalText',
        'text',
        'photoLink',
        'contentCategory1',
        'contentCategory2',
        'contentCategory3',
        'tag1',
        'tag2',
        'tag3',
        'tag4',
        'sponsor',
        'createAslide'
    ];

    const BATCH_SOURCE_DATA = [
        'identifier',
        'interviewer',
        'interviewee',
        'interviewDate',
        'attribution',
        'subAttribution',
        'organization',
        'topic',
        'email',
        'telephone',
        'courseOrProject',
        'interviewType'
    ];

    public function __construct(
        Component\SecureContainer $secureContainer,
        Component\RecognitionAdapter $recognitionAdapter,
        Service\QuoteLookup $quoteLookup,
        Service\QuoteManagement $quoteManagement
    ) {
        parent::__construct($secureContainer);

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

    protected function postBatch($request)
    {
        $files = $request->files->get('file');
        if (sizeof($files) != 2) {
            throw new \RuntimeException();
        }

        // there may be a better way to do this, for now we are just relying on file names to indicate which document
        foreach($request->files->get('file') as $file) {
            if (strpos(strtolower($file->getClientOriginalName()),"quote") !== false) $quote = $file;
            else if (strpos(strtolower($file->getClientOriginalName()),"source") !== false) $source = $file;
        }
        if (! (isset($source) && (isset($quote)))) throw new \RuntimeException();

        $quoteFilePath = $quote->getPathname();
        $sourceFilePath = $source->getPathname();
        $listOfQuotes = [];
        // There will be errors/warnings on three levels: top level (column names), source level (relating to source info), quotes level (relating to quotes info)
        $listOfQuotes["errors"] = [];
        $listOfQuotes["warnings"] = [];
        $columnOrder = []; // used to track column locations since we are going entirely by name instead of order

        // first pass through source sheet, creating entry for each interview. Later we will add list of quotes for each interview
        if (($f = fopen($sourceFilePath, "r")) !== FALSE)
        {
          $columnNames = fgetcsv($f);
          foreach ($columnNames as $column) {
              array_push($columnOrder,$column);
              if(!in_array($column,self::BATCH_SOURCE_DATA)) {
                  array_push($listOfQuotes["warnings"],"column " . $column . " is unrecognized.");
              }
          }
          if(! in_array("Attribution",$columnOrder)) {
              array_push($listOfQuotes["errors"],self::ERR_NO_ATTRIBUTIONS);
          }

          while (($data = fgetcsv($f)) !== FALSE)
          {
              $dataToAdd = ['errors' => []];
              $identifier = "";
              for ($i = 0; $i < count($columnNames); $i++) {
                  $columnName = $columnOrder[$i];
                  $currentColumnData = $data[$i];
                  if(str_contains(lower($columnName),"identifier")) $identifier = $currentColumnData;
                  else $dataToAdd[$columnName] = $currentColumnData;
              }
              $attributionIncluded = false;
              $identifierIncluded = false;
              foreach($dataToAdd as $key->$value) {
                  if(str_contains(lower($key),"attribution") && $value) {
                      $identifierIncluded = true;
                  } else if (str_contains(lower($key),"attribution") && $value){
                      $attributionIncluded = true;
                  }
              }
              $listOfQuotes[$identifier] = $dataToAdd;
              if (! $identifierIncluded) {
                  array_push($dataToAdd['errors'], self::ERR_MISSING_IDENTIFIER);
              }
              if (! $attributionIncluded) {
                  array_push($dataToAdd['errors'], self::ERR_MISSING_ATTRIBUTION);
              }
              fclose($f);
              var_dump($listOfQuotes);
              die();

              $identifier = $data[0]; // first column of each row is expected to give identifier, which we will use to store all quote info
              $listOfQuotes[$identifier] = [];
              $currentSource = $listOfQuotes[$identifier];
              $currentSource["errors"] = [];
              for ($i = 1; $i < count(self::BATCH_SOURCE_DATA); $i++) {
                  $currentSource[self::BATCH_SOURCE_DATA[$i]] = $data[$i];
              }
              $currentSource["errors"] = [];
              if(empty($currentSource["attribution"])) array_push($currentSource["errors"],self::ERR_ATTRIBUTION_REQUIRED);
          }
          fclose($f);
       }

        if (($f= fopen($quoteFilePath, "r")) !== FALSE)
        {
          fgetcsv($f); // first row is just column names so we should skip this.

          while (($data = fgetcsv($f)) !== FALSE)
          {
              $identifier = $data[0]; // first column of each row is expected to give identifier, which we will use to store all quote info
              if (array_key_exists($identifier,$listOfQuotes)) { // each quote should have an identifier corresponding to source information
                  if (! array_key_exists("quotes",$listOfQuotes[$identifier])) {
                      $listOfQuotes[$identifier]["quotes"] = []; // quotes array for each interview
                  }
                  $newQuote = [];
                  for ($i = 1; $i < count(self::BATCH_QUOTE_DATA); $i++) {
                      $newQuote[self::BATCH_QUOTE_DATA[$i]] = $data[$i];
                  }
                  array_push($listOfQuotes[$identifier]["quotes"],$newQuote);

                  $currentQuote = end($listOfQuotes[$identifier]["quotes"]);
                  $currentQuote["errors"] = [];
                  $currentQuote["warnings"] = [];
                  if(empty($currentQuote["contentCategory1"]) && empty($currentQuote["contentCategory2"]) && empty($currentQuote["contentCategory3"]))
                    array_push($currentQuote["errors"],ERR_MISSING_CONTENT_CATEGORY);
                  if(empty($currentQuote["text"]))
                    array_push($currentQuote["warnings"],WARNING_EMPTY_QUOTE);
              } else {
                  array_push($listOfQuotes["errors"], "source data could not be found for " . $identifier);
              }
          }

        // Close the file
        fclose($q);
        }
        // Display the code in a readable format
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
            $this->getFormAttributes($request, self::FORM_ATTRIBUTES, $request->request->get('defaults'))
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

    public function getQuoteRelatedSlide($request)
    {
        $id = $request->attributes->get('id');
        $this->quoteLookup->relatedSlide2($id);
    }

    public function getQuotePrevQuote($request)
    {
        $id = $request->attributes->get('id');
        $this->quoteLookup->prevQuote2($id);
    }

    public function getQuoteNextQuote($request)
    {
        $id = $request->attributes->get('id');
        $this->quoteLookup->nextQuote2($id);
    }
}
