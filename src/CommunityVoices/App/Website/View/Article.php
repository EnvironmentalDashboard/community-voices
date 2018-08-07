<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;
use Symfony\Component\Routing\Generator\UrlGenerator;

class Article extends Component\View
{
    protected $recognitionAdapter;
    protected $articleAPIView;
    protected $secureContainer;
    protected $transcriber;

    public function __construct(
        Component\RecognitionAdapter $recognitionAdapter,
        Component\MapperFactory $mapperFactory,
        Component\Transcriber $transcriber,
        Api\Component\SecureContainer $secureContainer,
        Api\View\Article $articleAPIView
    ) {
        $this->recognitionAdapter = $recognitionAdapter;
        $this->mapperFactory = $mapperFactory;
        $this->transcriber = $transcriber;
        $this->secureContainer = $secureContainer;
        $this->articleAPIView = $articleAPIView;
    }

    public function getArticle($routes, $context)
    {
        /**
         * Gather identity information
         */
        $identity = $this->recognitionAdapter->identify();

        $identityXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Gather article information
         */
        $articleAPIView = $this->secureContainer->contain($this->articleAPIView);

        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $articleAPIView->getArticle()->getContent()
            ))
        );
        // var_dump($articleXMLElement->title->asXML());die;

        /**
         * Article XML Package
         */
        $articlePackageElement = new Helper\SimpleXMLElementExtension('<package/>');

        $packagedArticle = $articlePackageElement->addChild('domain');
        $packagedArticle->adopt($articleXMLElement);

        $packagedIdentity = $articlePackageElement->addChild('identity');
        $packagedIdentity->adopt($identityXMLElement);

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
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getAllArticle($routes, $context)
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
         * Gather article information
         */
        $articleAPIView = $this->secureContainer->contain($this->articleAPIView);

        $json = json_decode($articleAPIView->getAllArticle()->getContent());
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
        $packagedIdentity->adopt($identityXMLElement);

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
        

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getArticleUpload($routes, $context)
    {
        try {
            $articleAPIView = $this->secureContainer->contain($this->articleAPIView);
            $articleAPIView->getArticleUpload();
        } catch (Exception $e) {
            echo $e->getMessage();
            return;
        }
        $paramXML = new SimpleXMLElement('<form/>');

        $formModule = new Component\Presenter('Module/Form/ArticleUpload');
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
            "Community Voices: Article Upload"
        );
        

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpload($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainXMLElement->addChild(
          'title',
          "Community Voices"
        );
        

        /**
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function getArticleUpdate($routes, $context)
    {
        $paramXML = new Helper\SimpleXMLElementExtension('<form/>');

        /**
         * Gather article information
         */
        $articleAPIView = $this->secureContainer->contain($this->articleAPIView);

        $articleXMLElement = new SimpleXMLElement(
            $this->transcriber->toXml(json_decode(
                $articleAPIView->getArticle()->getContent()
            ))
        );

        $packagedArticle = $paramXML->addChild('domain');
        $packagedArticle->adopt($articleXMLElement);

        $formModule = new Component\Presenter('Module/Form/ArticleUpdate');
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
            "Community Voices: Article Update"
        );
        

        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        // var_dump($domainIdentity);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }

    public function postArticleUpdate($routes, $context)
    {
        $identity = $this->recognitionAdapter->identify();
        $identityXMLElement = new SimpleXMLElement(
          $this->transcriber->toXml($identity->toArray())
        );

        /**
         * Get base URL
         */
        //$urlGenerator = new UrlGenerator($routes, $context);
        //$baseUrl = $urlGenerator->generate('root');

        $domainXMLElement = new Helper\SimpleXMLElementExtension('<domain/>');

        $domainXMLElement->addChild('main-pane', '<p>Success.</p>');
        //$domainXMLElement->addChild('baseUrl', $baseUrl);

        $domainXMLElement->addChild(
          'title',
          "Community Voices"
        );
        

        /**
         * Prepare template
         */
        $domainIdentity = $domainXMLElement->addChild('identity');
        $domainIdentity->adopt($identityXMLElement);

        $presentation = new Component\Presenter('SinglePane');

        $response = new HttpFoundation\Response($presentation->generate($domainXMLElement));

        $this->finalize($response);
        return $response;
    }
}
