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

class Article extends Component\View
{
    //protected $articleAPIView;
    //protected $articleLookup;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        //Api\View\Identification $identificationAPIView,
        Component\ApiProvider $apiProvider
        //Api\View\Article $articleAPIView,
        //Service\ArticleLookup $articleLookup
    ) {
        parent::__construct($mapperFactory, $transcriber, $apiProvider);

        //$this->articleAPIView = $articleAPIView;
        //$this->articleLookup = $articleLookup;
    }

    public function getArticle($request)
    {
        /**
         * Gather article information
         */
        $id = $request->attributes->get('id');
        $json = $this->apiProvider->getJson("/articles/{$id}", $request);
        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($json)
        );
        // var_dump($articleXMLElement->title->asXML());die;

        /**
         * Article XML Package
         */
        $articlePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedArticle = $articlePackageElement->addChild('domain');
        $packagedArticle->adopt($articleXMLElement);
        $encodedTitle = rawurlencode($json->article->title);
        $packagedArticle->adopt(new SimpleXMLElement(
            $this->transcriber->toXml(['relatedSlides' => $this->apiProvider->getQueriedJson("/articles/{$encodedTitle}/slides", $request)])
        ));
        // var_dump($packagedArticle);die;

        $packagedIdentity = $articlePackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        /**
         * Generate Article module
         */
        $articleModule = new Component\Presenter('Module/Article');
        $articleModuleXML = $articleModule->generate($articlePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $articleModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild(
            'title',
            "Community Voices: Article ".
            $articleXMLElement->id
        );


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getAllArticle($request)
    {
        parse_str($_SERVER['QUERY_STRING'], $qs);

        /**
         * Gather article information
         */
        $json = $this->apiProvider->getQueriedJson('/articles', $request);
        $obj = new \stdClass();
        $obj->articleCollection = (array) $json->articleCollection;
        $count = $obj->articleCollection['count'];
        $limit = $obj->articleCollection['limit'];
        $page = $obj->articleCollection['page'];
        unset($obj->articleCollection['count']);
        unset($obj->articleCollection['limit']);
        unset($obj->articleCollection['page']);
        foreach ($obj->articleCollection as $key => $article) {
            $article->article->text = htmlspecialchars($article->article->text);
            $article->article->title = htmlspecialchars($article->article->title);
            $article->article->author = htmlspecialchars($article->article->author);
        }
        $obj->articleCollection = array_values($obj->articleCollection);

        $tags = $json->tags;
        $tagXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($tags)
        );

        $authors = $json->authors;
        $authorXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($authors)
        );

        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($obj)
        );

        $pagination = new \stdClass();
        $pagination->div = $this->paginationHTML($qs, $count, $limit, $page);
        $paginationXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($pagination)
        );

        /**
         * Article XML Package
         */
        $articlePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedArticle = $articlePackageElement->addChild('domain');
        $packagedArticle->adopt($articleXMLElement);
        $packagedArticle->adopt($tagXMLElement);
        $packagedArticle->adopt($authorXMLElement);
        $packagedArticle->adopt($paginationXMLElement);
        // var_dump($packagedArticle);die;

        foreach ($qs as $key => $value) {
            if ($key === 'search' || $key === 'order' || $key === 'unused') {
                $packagedArticle->addChild($key, $value);
            } else {
                $packagedArticle->addChild($key, (is_array($value)) ? ','.implode(',', $value).',' : ','.$value.',');
            }
        }

        $packagedIdentity = $articlePackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement($request));

        /**
         * Generate Article module
         */
        $articleModule = new Component\Presenter('Module/ArticleCollection');
        $articleModuleXML = $articleModule->generate($articlePackageElement);

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        /**
         * Prepare template
         */
        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', $articleModuleXML);
        //$domainXMLElement->addChild('baseUrl', $baseUrl);
        $domainXMLElement->addChild('title', "Community Voices: All Articles");
        $domainXMLElement->addChild('extraJS', "article-collection");
        $domainXMLElement->addChild('metaDescription', "Searchable database of articles from interviews used to develop content for Community Voices communication technology to advance sustainability in diverse communities.");


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getArticleUpload($request)
    {
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Component\Presenter('Module/Form/ArticleUpload');
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
            "Community Voices: Article Upload"
        );


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpload($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            dirname($request->headers->get('referer'))
        );

        $this->finalize($response);
        return $response;
    }

    public function getArticleUpdate($request)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        /**
         * Gather article information
         */
        $id = $request->attributes->get('id');
        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(
                $this->apiProvider->getJson("/articles/{$id}", $request)
            )
        );

        $packagedArticle = $paramXML->addChild('domain');
        $packagedArticle->adopt($articleXMLElement);

        $formModule = new Component\Presenter('Module/Form/ArticleUpdate');
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
            "Community Voices: Article Update"
        );


        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($this->identityXMLElement($request));

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpdate($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            dirname($request->headers->get('referer'))
        );

        $this->finalize($response);
        return $response;
    }
}
