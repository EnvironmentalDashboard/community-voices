<?php

namespace CommunityVoices\App\Website\View;

use \SimpleXMLElement;
use \DOMDocument;
use \XSLTProcessor;

use CommunityVoices\App\Api;
use CommunityVoices\App\Website\Component;
use Symfony\Component\HttpFoundation;

class Location extends Component\View
{
    protected $locationAPIView;
    protected $secureContainer;

    public function __construct(
      Api\View\Location $locationAPIView,
      Api\Component\SecureContainer $secureContainer
    ) {
        $this->locationAPIView = $locationAPIView;
        $this->secureContainer = $secureContainer;
    }

    public function getAllLocation($request)
    {
      $locationAPIView = $this->secureContainer->contain($this->locationAPIView);
      $json = json_decode($locationAPIView->getAllImage()->getContent());

      $response = new HttpFoundation\Response();

      $response->setContent(json_encode($json));

      return $response;
    }
}
