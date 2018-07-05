<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Article
{
    protected $articleLookup;
    protected $articleManagement;
    protected $imageManagement;

    public function __construct(
        Service\ArticleLookup $articleLookup,
        Service\ArticleManagement $articleManagement,
        Service\ImageManagement $imageManagement
    ){
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

        $this->articleLookup->findById($articleId);
    }

    public function getAllArticle($request, $identity)
    {
        $creatorIDs = $request->attributes->get('creatorIDs');
        $status = $request->attributes->get('status');

        $status = ($status == Null) ? ["approved","pending","rejected"] : $status;
        if($identity->getRole() <= 2){
          $status = ["approved"];
        }

        $this->articleLookup->findAll($creatorIDs, $status);
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
        
        if($identity->getRole() <= 2){
          $approved = null;
        }

        $uploaded_image = $this->imageManagement->upload($file, null, null, $dateRecorded, null, null, $identity, $approved, null);

        $this->articleManagement->upload($uploaded_image, $text, $author, $dateRecorded, $approved, $identity);
    }

    public function getArticleUpdate($request)
    {
      $articleId = $request->attributes->get('id');

      $this->articleLookup->findById($articleId);
    }

    public function postArticleUpdate($request)
    { // TODO
      $file = $request->files->get('file');
      $text = $request->request->get('text');
      $author = $request->request->get('author');
      $dateRecorded = $request->request->get('dateRecorded');
      $status = $request->request->get('status');
      $id = $request->request->get('id');

      $this->articleManagement->update($id, $text, $author, $dateRecorded, $status);
    }
}
