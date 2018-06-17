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
        $this->imageLookup->findAll();
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
