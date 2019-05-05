<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Api\Component;

class Article extends Component\Controller
{
    protected $articleLookup;
    protected $articleManagement;
    protected $imageManagement;

    public function __construct(
        Service\ArticleLookup $articleLookup,
        Service\ArticleManagement $articleManagement,
        Service\ImageManagement $imageManagement
    ) {
        $this->articleLookup = $articleLookup;
        $this->articleManagement = $articleManagement;
        $this->imageManagement = $imageManagement;
    }

    /**
     * Article lookup by id
     */
    public function getArticle($request)
    {
        $articleId = $request->attributes->get('id');

        try {
            $this->articleLookup->findById((int) $articleId);
        } catch (Exception\IdentityNotFound $e) {
            $this->send404();
        }
    }

    public function getAllArticle($request, $identity)
    {
        $search = (string) $request->query->get('search');
        $tags = $request->query->get('tags');
        $authors = $request->query->get('authors');

        $creatorIDs = $request->attributes->get('creatorIDs');
        $status = $request->query->get('status');

        $status = ($status == null) ? ["approved","pending","rejected"] : explode(',', $status);
        if ($identity->getRole() <= 2) {
            $status = ["approved"];
        }

        $order = (string) $request->query->get('order');
        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = 10; // number of items per page
        $offset = $limit * $page;
        $this->articleLookup->findAll($page, $limit, $offset, $order, $search, $tags, $authors, $creatorIDs, $status);
    }

    public function getArticleUpload()
    {
        // intentionally blank
    }

    public function postArticleUpload($request, $identity)
    {
        $file = $request->files->get('file');
        $text = $request->request->get('text');
        $author = $request->request->get('author');
        $approved = $request->request->get('approved');
        $dateRecorded = $request->request->get('dateRecorded');
        $strtotime = strtotime($dateRecorded);
        $dateRecorded = ($strtotime) ? $strtotime : time();

        if ($identity->getRole() <= 2) {
            $approved = null;
        }

        $uploaded_images = $this->imageManagement->upload([$file], null, null, $dateRecorded, null, null, $identity, $approved, null);

        $this->articleManagement->upload($uploaded_images[0], $text, $author, $dateRecorded, $approved, $identity);
    }

    public function getArticleUpdate($request)
    {
        $articleId = $request->attributes->get('id');

        try {
            $this->articleLookup->findById((int) $articleId);
        } catch (Exception\IdentityNotFound $e) {
            $this->send404();
        }
    }

    public function postArticleUpdate($request)
    {
        // $file = $request->files->get('file');
        $text = $request->request->get('text');
        $title = $request->request->get('title');
        $author = $request->request->get('author');
        $dateRecorded = $request->request->get('dateRecorded');
        $status = $request->request->get('status');
        $id = $request->attributes->get('id');//$request->request->get('id');

        $this->articleManagement->update($id, $text, $title, $author, $dateRecorded, $status);
    }
}
