<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Image
{
    protected $imageLookup;
    protected $imageManagement;

    public function __construct(
        Service\ImageLookup $imageLookup,
        Service\ImageManagement $imageManagement
    ) {
        $this->imageLookup = $imageLookup;
        $this->imageManagement = $imageManagement;
    }

    public function sendImage($request)
    {
        $imageId = $request->attributes->get('id');

        $this->imageLookup->printById((int) $imageId);
    }

    /**
     * image lookup by id
     */
    public function getImage($request)
    {
        $imageId = $request->attributes->get('id');

        $this->imageLookup->findById((int) $imageId);
    }

    public function getAllImage($request)
    {
        $sort = $request->query->get('sort');
        $sort = ($sort === 'title' || $sort === 'photographer' || $sort === 'date_taken') ? $sort : 'date_taken';
        
        $order = $request->query->get('order');
        $order = ($order === 'DESC' || $order === 'ASC') ? $order : 'DESC';
        
        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = 2; // number of items per page
        $offset = $limit * $page;
        
        $this->imageLookup->findAll($limit, $offset, $sort, $order);
    }

    public function getImageUpload()
    {
        // intentionally blank
    }

    public function postImageUpload($request, $identity)
    {
        $file = $request->files->get('file');
        $title = $request->request->get('title');
        $description = $request->request->get('description');
        $dateTaken = $request->request->get('dateTaken');
        $photographer = $request->request->get('photographer');
        $organization = $request->request->get('organization');
        $approved = $request->request->get('approved');

        $this->imageManagement->upload(
          $file,
          $title,
          $description,
          $dateTaken,
          $photographer,
          $organization,
          $identity,
          $approved
      );
    }

    public function getImageUpdate($request)
    { // what does this do??
      $imageId = $request->attributes->get('id');
      $this->imageLookup->findById($imageId);
    }

    public function postImageUpdate($request)
    {
      $id = $request->request->get('id');
      $title = $request->request->get('title');
      $description = $request->request->get('description');
      $dateTaken = $request->request->get('dateTaken');
      $photographer = $request->request->get('photographer');
      $organization = $request->request->get('organization');
      $status = ($request->request->get('approved') !== null) ? 'approved' : 'pending';

      $this->imageManagement->update(
        $id,
        $title,
        $description,
        $dateTaken,
        $photographer,
        $organization,
        $status
      );
    }
    // 
}
