<?php

namespace CommunityVoices\App\Website\Component;

use CommunityVoices\App\Website\Component\Mapper;

class View
{
    protected $mapperFactory;

    public function __construct(
        MapperFactory $mapperFactory
    ) {
        $this->mapperFactory = $mapperFactory;
    }

    protected function finalize($response)
    {
        $cookieMapper = $this->mapperFactory->createCookieMapper(Mapper\Cookie::class);

        $cookieMapper->provideResponseHandler($response);
        $cookieMapper->mapToResponse();
    }
}
