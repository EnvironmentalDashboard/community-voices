<?php

namespace CommunityVoices\Model\Service;

/**
 * @overview Handles lookup functionality for domain-related entities.
 */

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;

class Lookup
{
    private $mapperFactory;

    public function __construct(Component\MapperFactory $mapperFactory)
    {
        $this->mapperFactory = $mapperFactory;
    }

    public function findImages()
    {
        $images = new Entity\ImageCollection;

        $mapper = new Mapper\ImageCollection;
        $mappper->fetch($images);

        return $images;
    }
}
