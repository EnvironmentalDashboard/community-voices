<?php

namespace CommunityVoices\App\Api\View;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class Identification extends Component\View
{
    protected $recognitionAdapter;

    public function __construct(
        Component\SecureContainer $secureContainer,
        MapperFactory $mapperFactory,
        Component\RecognitionAdapter $recognitionAdapter
    ) {
        parent::__construct($secureContainer, $mapperFactory);

        $this->recognitionAdapter = $recognitionAdapter;
    }

    protected function getIdentity()
    {
        $identity = $this->recognitionAdapter->identify();

        $response = new HttpFoundation\JsonResponse($identity->toArray());

        return $response;
    }

    protected function postCredentials()
    {
        $identity = $this->recognitionAdapter->identify();

        $response = new HttpFoundation\JsonResponse();

        if ($identity) {
            $response->setData(["errors" => ["Could not log in."]]);
        } else {
            $response->setData(["errors" => []]);
        }

        return $response;
    }

    protected function getLogout()
    {
        $identity = $this->recognitionAdapter->identify();

        $response = new HttpFoundation\JsonResponse();

        if ($identity) {
            $response->setData(["errors" => []]);
        } else {
            $response->setData(["errors" => ["Could not log out."]]);
        }

        return $response;
    }
}
