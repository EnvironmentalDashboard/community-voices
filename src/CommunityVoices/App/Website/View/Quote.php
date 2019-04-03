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
    protected $recognitionAdapter;
    protected $mapperFactory;
    protected $transcriber;
    protected $secureContainer;
    protected $quoteAPIView;
    protected $quoteLookup;
    protected $tagLookup;
    protected $tagAPIView;
    protected $contentCategoryAPIView;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Quote $quoteAPIView,
        Service\QuoteLookup $quoteLookup,
        Service\TagLookup $tagLookup,
        Api\View\Tag $tagAPIView,
        Api\View\ContentCategory $contentCategoryAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->quoteAPIView = $quoteAPIView;
        $this->quoteLookup = $quoteLookup;
        $this->tagLookup = $tagLookup;
        $this->tagAPIView = $tagAPIView;
        $this->contentCategoryAPIView = $contentCategoryAPIView;
    }

    public function getQuote($request)
    {
        /**
         * Gather identity information (API component call)
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather quote information (API calls)
         */
        $quoteAPIView = $this->secureContainer->contain($this->quoteAPIView);

        $quote = json_decode($quoteAPIView->getQuote()->getContent());
        $boundaryQuotes = json_decode($quoteAPIView->getBoundaryQuotes()->getContent(), true);

        /**
         * Process API information
         */
        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($quote)
        );

        $prevQuoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($boundaryQuotes['quoteCollection'][0])
        );

        $nextQuoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($boundaryQuotes['quoteCollection'][1])
        );

        /**
         * Quote XML "package"
         */
        $quotePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedQuote = $quotePackageElement->addChild('domain');
        $packagedQuote->adopt($quoteXMLElement);

        $packagedQuote->adopt(new SimpleXMLElement(
            $this->transcriber->toXml(['slideId' => $this->quoteLookup->relatedSlide($quote->quote->id)])
        ));

        $previousQuote = $packagedQuote->addChild('previous');
        $previousQuote->adopt($prevQuoteXMLElement);

        $nextQuote = $packagedQuote->addChild('next');
        $nextQuote->adopt($nextQuoteXMLElement);

        $packagedIdentity = $quotePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

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
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getAllQuote($request)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather quote information
         */
        $quoteAPIView = $this->secureContainer->contain($this->quoteAPIView);
        $json = json_decode($quoteAPIView->getAllQuote()->getContent());

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
            $quote->quote->relatedSlide = $this->quoteLookup->relatedSlide($quote->quote->id);
        }
        $obj->quoteCollection = array_values($obj->quoteCollection);

        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $tags = $json->tags;
        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($tags)
        );

        $contentCategories = $json->contentCategories;
        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($contentCategories)
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
        $packagedIdentity->adopt($identityXMLElement);

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
        $domainXMLElement->addChild('metaDescription', "Searchable database of quotes used for Community Voices communication technology to promote environmental, social and economic sustainability in diverse communities.");

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getQuoteUpload($request)
    {
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        $tagAPIView = $this->secureContainer->contain($this->tagAPIView);
        $contentCategoryAPIView = $this->secureContainer->contain($this->contentCategoryAPIView);

        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $tagAPIView->getAllTag()->getContent()
            ))
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $contentCategoryAPIView->getAllContentCategory()->getContent()
            ))
        );

        $quotePackageElement = new Helper\SimpleXMLElementExtension('<package/>');
        $packagedQuote = $quotePackageElement->addChild('domain');
        $packagedQuote->adopt($tagXMLElement);
        $packagedQuote->adopt($contentCategoryXMLElement);
        $packagedIdentity = $quotePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);
        $quoteModule = new Component\Presenter('Module/Form/QuoteUpload');
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
        $domainXMLElement->addChild('title', "Community Voices: Quote Upload");
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);
        $presentation = new Component\Presenter('SinglePane');
        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));
        $this->finalize($response);
        return $response;
    }

    public function postQuoteUpload($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
        );

        $this->finalize($response);
        return $response;

        /*
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');
        $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        $domainXMLElement->addChild(
          'title',
          "Community Voices"
        );
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);
        $presentation = new Component\Presenter('SinglePane');
        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));
        $this->finalize($response);
        return $response;
        */
    }

    public function getQuoteUpdate($request)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        /**
         * Gather quote information
         */
        $quoteAPIView = $this->secureContainer->contain($this->quoteAPIView);

        $quote = json_decode($quoteAPIView->getQuote()->getContent());
        $quote->quote->text = htmlspecialchars($quote->quote->text);
        $quoteXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($quote)
        );

        $tagAPIView = $this->secureContainer->contain($this->tagAPIView);
        $contentCategoryAPIView = $this->secureContainer->contain($this->contentCategoryAPIView);

        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $tagAPIView->getAllTag()->getContent()
            ))
        );

        $contentCategoryXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $contentCategoryAPIView->getAllContentCategory()->getContent()
            ))
        );

        $selectedGroupString = ',';
        foreach ($quote->quote->tagCollection->groupCollection as $group) {
            $selectedGroupString .= "{$group->group->id},";
        }
        foreach ($quote->quote->contentCategoryCollection->groupCollection as $group) {
            $selectedGroupString .= "{$group->group->id},";
        }
        $selectedGroupXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(['selectedGroups' => [$selectedGroupString]])
        );

        $packagedQuote = $paramXML->addChild('domain');
        $packagedQuote->adopt($quoteXMLElement);
        $packagedQuote->adopt($tagXMLElement);
        $packagedQuote->adopt($contentCategoryXMLElement);
        $packagedQuote->adopt($selectedGroupXMLElement);

        $formModule = new Component\Presenter('Module/Form/QuoteUpdate');
        $formModuleXML = $formModule->generate($paramXML);

        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

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


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        // var_dump($domainIdentity);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postQuoteUpdate($request)
    {
        $quoteAPIView = $this->secureContainer->contain($this->quoteAPIView);
        $errors = json_decode($quoteAPIView->postQuoteUpdate()->getContent());

        if (!empty($errors->errors)) {
            return $this->getQuoteUpdate($request);
        }

        // Should be some logic to go back to the edit form
        // if errors exist.
        // But, for now, we simply will show the edited quote.
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
