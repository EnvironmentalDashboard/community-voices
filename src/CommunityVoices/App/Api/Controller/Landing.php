<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\Model\Service;

class Landing
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
    public function getLanding($request)
    {
        $this->slideLookup->findAll(
            0, // page
            5, // limit
            0, // offset
            'rand', // order
            '', // search
            null, // tags
            null, // photographers
            null, // orgs
            null, // attributions
            [1] // content category
        );
    }
}
