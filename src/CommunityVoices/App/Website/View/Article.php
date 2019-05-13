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
    protected $articleAPIView;
    protected $articleLookup;

    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\View\Identification $identificationAPIView,
        Api\View\Article $articleAPIView,
        Service\ArticleLookup $articleLookup
    ) {
        parent::__construct($mapperFactory, $transcriber, $identificationAPIView);

        $this->articleAPIView = $articleAPIView;
        $this->articleLookup = $articleLookup;
    }

    public function getArticle($request)
    {
        /**
         * Gather article information
         */
        $json = json_decode($this->articleAPIView->getArticle()->getContent());
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
        $packagedArticle->adopt(new SimpleXMLElement(
            $this->transcriber->toXml(['relatedSlides' => $this->articleLookup->relatedSlides($json->article->title)])
        ));
        // var_dump($packagedArticle);die;

        $packagedIdentity = $articlePackageElement->addChild('identity');
        $packagedIdentity->adopt($this->identityXMLElement());

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
        $domainIdentity->adopt($this->identityXMLElement());

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
        $json = json_decode($this->articleAPIView->getAllArticle()->getContent());
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
        $packagedIdentity->adopt($this->identityXMLElement());

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
        $domainIdentity->adopt($this->identityXMLElement());

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getArticleUpload($request)
    {
        try {
            $this->articleAPIView->getArticleUpload();
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
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
        $domainIdentity->adopt($this->identityXMLElement());

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpload($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
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
        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $this->articleAPIView->getArticle()->getContent()
            ))
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
        $domainIdentity->adopt($this->identityXMLElement());

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpdate($request)
    {
        $response = new HttpFoundation\RedirectResponse(
            $request->headers->get('referer')
        );

        $this->finalize($response);
        return $response;
    }
}
