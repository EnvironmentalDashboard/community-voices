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
        Service\ImageManagement $imageManagement)
    {
        $this->imageLookup = $imageLookup;
        $this->imageManagement = $imageManagement;
    }

    /**
     * image lookup by id
     */
    public function getImage($request)
    {
        $imageId = $request->attributes->get('id');

        $this->imageLookup->findById($imageId);
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
      $file = $request->request->get('filename'); // what do we do with this here??
      $title = $request->request->get('title');
      $description = $request->request->get('description');
      $dateTaken = $request->request->get('dateTaken');
      $photographer = $request->request->get('photographer');
      $organization = $request->request->get('organization');

      var_dump($file);

      $this->imageManagement->upload($file, $title, $description, $dateTaken,
                                      $photographer, $organization, $identity);
    }
}
