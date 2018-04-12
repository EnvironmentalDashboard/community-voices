<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Slide
{
    protected $slideLookup;
    protected $slideManagement;

    public function __construct(
        Service\SlideLookup $slideLookup// ,
        // Service\SlideManagement $slideManagement
    ){
        $this->slideLookup = $slideLookup;
        // $this->slideManagement = $slideManagement;
    }

    public function getAllSlides($request){
        $this->slideLookup->findAll();
    }

    /**
     * Slide lookup by id
     */
    public function getSlide($request)
    {
        $slideId = $request->attributes->get('id');

        $this->slideLookup->findById($slideId);
    }
}
