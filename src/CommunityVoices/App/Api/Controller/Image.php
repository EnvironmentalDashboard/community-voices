<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Image
{
    protected $imageLookup;


    public function __construct(
        Service\ImageLookup $imageLookup
    ){
        $this->imageLookup = $imageLookup;
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
}
