<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Slide
{
    protected $slideLookup;
    protected $slideManagement;

    public function __construct(
        Service\SlideLookup $slideLookup,
        Service\SlideManagement $slideManagement
    ) {
        $this->slideLookup = $slideLookup;
        $this->slideManagement = $slideManagement;
    }

    /**
     * Grabs all slides from databbase
     * @param  Request $request A request from the client's machine
     * @return SlideCollection  A collection of all slides in the database
     */
    public function getAllSlide($request)
    {
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
