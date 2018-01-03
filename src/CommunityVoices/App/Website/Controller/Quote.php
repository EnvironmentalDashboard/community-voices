<?php

namespace CommunityVoices\App\Website\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\App\Website\Component;
use CommunityVoices\App\Api;
use Fracture\Http;

class Quote
{
    protected $quoteAPIController;
    protected $secureContainer;

    public function __construct(
        Api\Controller\Quote $quoteAPIController,
        Api\Component\SecureContainer $secureContainer
    ) {
        $this->quoteAPIController = $quoteAPIController;
        $this->secureContainer = $secureContainer;
    }

    public function getQuote($request)
    {
        $apiController = $this->secureContainer->contain($this->quoteAPIController);

        $apiController->getQuote($request);
    }
}
