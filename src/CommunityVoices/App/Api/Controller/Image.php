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
        Service\ImageManagement $imageManagement,
        Service\TagLookup $tagLookup
    ) {
        $this->imageLookup = $imageLookup;
        $this->imageManagement = $imageManagement;
        $this->tagLookup = $tagLookup;
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

        $search = (string) $request->query->get('search');
        $tags = $request->query->get('tags');
        $photographers = $request->query->get('photographers');
        $orgs = $request->query->get('orgs');
        $order = (string) $request->query->get('order');
        
        $page = (int) $request->query->get('page');
        $page = ($page > 0) ? $page - 1 : 0; // current page, make page 0-based
        $limit = (int) $request->query->get('per_page');
        $limit = ($limit > 0) ? $limit : 15; // number of items per page
        $offset = $limit * $page;
        $only_unused = (int) $request->query->get('unused');
        $only_unused = ($only_unused === 0 || $only_unused === 1) ? $only_unused : 0;

        $this->imageLookup->findAll($page, $limit, $offset, $order, $only_unused, $search, $tags, $photographers, $orgs);
    }

    public function getImageUpload()
    {
        $this->tagLookup->findAll();
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
        $tags = $request->request->get('tags');

        $this->imageManagement->upload(
          $file,
          $title,
          $description,
          $dateTaken,
          $photographer,
          $organization,
          $identity,
          $approved,
          $tags
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
      $crop_x = (int) $request->request->get('crop_x');
      $crop_y = (int) $request->request->get('crop_y');
      $crop_width = (int) $request->request->get('crop_width');
      $crop_height = (int) $request->request->get('crop_height');
      $status = ($request->request->get('approved') !== null) ? 'approved' : 'pending';

      $this->imageManagement->update(
        $id,
        $title,
        $description,
        $dateTaken,
        $photographer,
        $organization,
        ['x' => $crop_x, 'y' => $crop_y, 'width' => $crop_width, 'height' => $crop_height],
        $status
      );
    }
    // 
}
