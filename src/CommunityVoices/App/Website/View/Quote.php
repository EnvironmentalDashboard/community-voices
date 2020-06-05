<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use CommunityVoices\Model\Service;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Quote extends Component\View
{
    //protected $quoteAPIView;
    //protected $quoteLookup;
    //protected $tagLookup;
    //protected $tagAPIView;
    //protected $contentCategoryAPIView;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider
        // Api\View\Quote $quoteAPIView,
        // Service\QuoteLookup $quoteLookup,
        // Service\TagLookup $tagLookup,
        // Api\View\Tag $tagAPIView,
        // Api\View\ContentCategory $contentCategoryAPIView
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        // $this->quoteAPIView = $quoteAPIView;
        // $this->quoteLookup = $quoteLookup;
        // $this->tagLookup = $tagLookup;
        // $this->tagAPIView = $tagAPIView;
        // $this->contentCategoryAPIView = $contentCategoryAPIView;
    }

    public function getQuote($request)
    {
        /**
         * Gather quote information (API calls)
         */
        $id = $request->attributes->get('id');
        $quote = $this->apiProvider->getJson("/quotes/{$id}", $request);
        $boundaryQuotes = json_decode($this->apiProvider->get("/quotes/{$id}/boundary", $request), true);

        /**
         * Process API information
         */
        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($quote)
        );

        // If we have two quotes in our return, then we know we have both a next
        // and a previous.
        // If we otherwise only have one quote, it could be either the next or
        // the previous and we need to compare IDs to know.
        if (key_exists(1, $boundaryQuotes['quoteCollection'])) {
            $prevQuoteXMLElement = new SimpleXMLElement(
                $this->transcriber->toXml($boundaryQuotes['quoteCollection'][0])
            );

            $nextQuoteXMLElement = new SimpleXMLElement(
                $this->transcriber->toXml($boundaryQuotes['quoteCollection'][1])
            );
        } else if (key_exists(0, $boundaryQuotes['quoteCollection'])) {
            // This logic is probably best kept out of a view.
            // TODO (but maybe it is fine in a view)
            $isPrev = $boundaryQuotes['quoteCollection'][0]['quote']['id'] < $quote->quote->id;
            $element = new SimpleXMLElement(
                $this->transcriber->toXml($boundaryQuotes['quoteCollection'][0])
            );

            if ($isPrev)
                $prevQuoteXMLElement = $element;
            else
                $nextQuoteXMLElement = $element;
        }

        /**
         * Quote XML "package"
         */
        $quotePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedQuote = $quotePackageElement->addChild('domain');
        $packagedQuote->adopt($quoteXMLElement);

        $packagedQuote->adopt(new SimpleXMLElement(
            $this->transcriber->toXml(['slideId' => $this->apiProvider->getJson("/quotes/{$id}/slide", $request)])
        ));

        if (isset($prevQuoteXMLElement)) {
            $previousQuote = $packagedQuote->addChild('previous');
            $previousQuote->adopt($prevQuoteXMLElement);
        }

        if (isset($nextQuoteXMLElement)) {
            $nextQuote = $packagedQuote->addChild('next');
            $nextQuote->adopt($nextQuoteXMLElement);
        }

        $packagedIdentity = $quotePackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        /**
         * Generate Quote module
         */
        $quoteModule = new Component\Presenter('Module/Quote');
        $quoteModuleXML = $quoteModule->generate($quotePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $quoteModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Quote ".
            $quoteXMLElement->id
        );


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getAllQuote($request)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        /**
         * Gather quote information
         */
        $json = $this->apiProvider->getQueriedJson('/quotes', $request);

        $obj = new \stdClass();
        $obj->quoteCollection = (array) $json->quoteCollection;
        $count = $obj->quoteCollection['count'];
        $limit = $obj->quoteCollection['limit'];
        $page = $obj->quoteCollection['page'];
        unset($obj->quoteCollection['count']);
        unset($obj->quoteCollection['limit']);
        unset($obj->quoteCollection['page']);
        foreach ($obj->quoteCollection as $key => $quote) {
            $quote->quote->text = $quote->quote->text;
            $quote->quote->attribution = $quote->quote->attribution;
            $quote->quote->subAttribution = $quote->quote->subAttribution;
            $quote->quote->relatedSlide = $quote->quote->relatedSlide;
        }
        $obj->quoteCollection = array_values($obj->quoteCollection);

        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/tags", $request)
            )
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/content-categories", $request)
            )
        );

        $pagination = new \stdClass();
        $pagination->div = $this->paginationHTML($qs, $count, $limit, $page);
        $paginationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($pagination)
        );

        $attributions = $json->quoteCollectionAttributions;
        $attributionXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($attributions)
        );

        $subattributions = $json->quoteCollectionSubAttributions;
        $subattributionXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($subattributions)
        );

        /**
         * Quote XML Package
         */
        $quotePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedQuote = $quotePackageElement->addChild('domain');
        $packagedQuote->adopt($quoteXMLElement);
        $packagedQuote->adopt($tagXMLElement);
        $packagedQuote->adopt($contentCategoryXMLElement);
        $packagedQuote->adopt($attributionXMLElement);
        $packagedQuote->adopt($subattributionXMLElement);
        $packagedQuote->adopt($paginationXMLElement);

        foreach ($qs as $key => $value) {
            if ($key === 'search' || $key === 'order' || $key === 'unused') {
                $packagedQuote->addChild($key, $value);
            } else {
                $packagedQuote->addChild($key, (is_array($value)) ? ','.implode(',', $value).',' : ','.$value.',');
            }
        }

        $packagedIdentity = $quotePackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        /**
         * Generate Quote module
         */
        $quoteModule = new Component\Presenter('Module/QuoteCollection');
        $quoteModuleXML = $quoteModule->generate($quotePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $quoteModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', "Community Voices: All Quotes");
        $domainXMLElement->addChild('extraJS', "quote-collection");
        $domainXMLElement->addChild('extraCSS', "quote-collection");
        $domainXMLElement->addChild('metaDescription', "Searchable database of quotes used for Community Voices communication technology to promote environmental, social and economic sustainability in diverse communities.");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getQuoteUpload($request, $errors = self::ERRORS_DEFAULT)
    {
        /**
         * Grab cached form
         */
        $formCache = new Component\CachedItem('quoteUploadForm');

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->fetch($formCache);

        $form = $formCache->getValue();

        if (!is_null($form)) {
            $formTags = $form['tags'];
            $formContentCategories = $form['contentCategories'];

            unset($form['tags']);
            unset($form['contentCategories']);

            $formParamXML = new Helper\SimpleXMLElementExtension(
                '<form>' . $this->transcriber->toXml($form) . '</form>'
            );
        }

        $errorsXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($errors)
        );

        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/tags", $request)
            )
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson('/content-categories', $request)
            )
        );

        $selectedGroupString = ',';
        $tagForEach = $formTags ?? [];
        $contentCategoryForEach = $formContentCategories ?? [];

        foreach ($tagForEach as $group) {
            $selectedGroupString .= "{$group},";
        }
        foreach ($contentCategoryForEach as $group) {
            $selectedGroupString .= "{$group},";
        }
        $selectedGroupXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(['selectedGroups' => [$selectedGroupString]])
        );

        $quoteFormElement = new Helper\SimpleXMLElementExtension('<form/>');
        $packagedQuote = $quoteFormElement->addChild('domain');

        $packagedQuote->adopt($tagXMLElement);
        $packagedQuote->adopt($contentCategoryXMLElement);
        $packagedQuote->adopt($errorsXMLElement);
        $packagedQuote->adopt($selectedGroupXMLElement);

        if (isset($formParamXML)) {
            $packagedQuote->adopt($formParamXML);
        }

        $packagedIdentity = $quoteFormElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));
        $quoteModule = new Component\Presenter('Module/Form/Quote');
        $quoteModuleXML = $quoteModule->generate($quoteFormElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');
        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');
        $domainXMLElement->addChild('main-pane', $quoteModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', "Community Voices: Quote Upload");
        $domainXMLElement->addChild('extraJS', 'quote-form');
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));
        $presentation = new Component\Presenter('SinglePane');
        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));
        $this->finalize($response);
        return $response;
    }

    public function postQuoteUpload($request, $errors = self::ERRORS_DEFAULT)
    {
    // There are three possible outcomes when the user enteres a new quote:
    // 1: Error with entering correct fields
    // 2: Correct fields entered, user presses "batch submit"
    // 3: Correct fields entered, user presses "submit"
    // in cases 1 and 2, want to bring user back to same page with same fields filled in,
    // in case 3, want to advance user to new screen showing their submitted quote
        if (!empty($errors->upload->errors) or $request->request->get('batch_submit')) {
            return $this->getQuoteUpload($request, $errors);
        }
        // if the user presses "submit"
        // We simply will show the edited quote.
        // dirname() removes the /new from the url we are
        // redirecting to.
        else {
            $response = new HttpFoundation\RedirectResponse(
                dirname($request->headers->get('referer')) . '/' . $errors->upload->quote->id[0]
            );
            $this->finalize($response);
            return $response;
        }
    }
    public function getQuoteUpdate($request, $errors = self::ERRORS_DEFAULT)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        /**
         * Grab cached form
         */
        $formCache = new Component\CachedItem('quoteUpdateForm');

        $cacheMapper = $this->mapperFactory->createCacheMapper();
        $cacheMapper->fetch($formCache);

        $form = $formCache->getValue();

        if (!is_null($form)) {
            $formTags = $form['tags'];
            $formContentCategories = $form['contentCategories'];

            unset($form['tags']);
            unset($form['contentCategories']);

            $formParamXML = new Helper\SimpleXMLElementExtension(
                '<form>' . $this->transcriber->toXml($form) . '</form>'
            );
        }

        $errorsXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($errors)
        );

        $id = $request->attributes->get('id');
        $quote = $this->apiProvider->getJson("/quotes/{$id}", $request);

        $quote->quote->text = htmlspecialchars($quote->quote->text);
        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($quote)
        );

        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/tags", $request)
            )
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson('/content-categories', $request)
            )
        );

        $selectedGroupString = ',';
        $tagForEach = isset($formTags) ? $formTags : $quote->quote->tagCollection->groupCollection;
        $contentCategoryForEach = isset($formContentCategories) ? $formContentCategories : $quote->quote->contentCategoryCollection->groupCollection;

        foreach ($tagForEach as $group) {
            $selectedGroupString .= is_object($group) ? "{$group->group->id}," : "{$group},";
        }
        foreach ($contentCategoryForEach as $group) {
            $selectedGroupString .= is_object($group) ? "{$group->group->id}," : "{$group},";
        }
        $selectedGroupXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(['selectedGroups' => [$selectedGroupString]])
        );

        $packagedQuote = $paramXML->addChild('domain');
        $packagedQuote->adopt($quoteXMLElement);
        $packagedQuote->adopt($tagXMLElement);
        $packagedQuote->adopt($contentCategoryXMLElement);
        $packagedQuote->adopt($selectedGroupXMLElement);
        $packagedQuote->adopt($errorsXMLElement);


        if (!is_null($form)) {
            $packagedQuote->adopt($formParamXML);
        }

        $formModule = new Component\Presenter('Module/Form/Quote');
        $formModuleXML = $formModule->generate($paramXML);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        //

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $formModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Quote Update"
        );
        $domainXMLElement->addChild('extraJS', 'quote-form');


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postQuoteUpdate($request, $errors = self::ERRORS_DEFAULT)
    {
        if (!empty($errors->errors)) {
            return $this->getQuoteUpdate($request, $errors);
        }

        // We simply will show the edited quote.
        // dirname() removes the /edit from the url we are
        // redirecting to
        $response = new HttpFoundation\RedirectResponse(
            dirname($request->headers->get('referer'))
        );

        $this->finalize($response);
        return $response;
    }

    public function postQuoteUnpair($request)
    {
        exit; // nothing to show to user
    }
}
