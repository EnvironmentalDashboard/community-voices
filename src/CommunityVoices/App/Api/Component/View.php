<?php

namespace CommunityVoices\App\Api\Component;

use Symfony\Component\HttpFoundation;

use CommunityVoices\Model\Component\MapperFactory;
use CommunityVoices\App\Api\Component;

class View extends Component\SecuredComponent
{
    protected $mapperFactory;

    public function __construct(
        Arbiter $arbiter,
        Contract\CanIdentify $identifier,
        \Psr\Log\LoggerInterface $logger,

        MapperFactory $mapperFactory
    ) {
        parent::__construct($arbiter, $identifier, $logger);

        $this->mapperFactory = $mapperFactory;
    }

    protected function errorsResponse($key)
    {
        $clientStateMapper = $this->mapperFactory->createClientStateMapper();
        $clientStateObserver = $clientStateMapper->retrieve();

        // In the case that we have retrieved errors, we will send them along.
        // Otherwise, our errors array will be an empty array.
        $errors = ($clientStateObserver && $clientStateObserver->hasSubjectEntries($key))
            ? $clientStateObserver->getEntriesBySubject($key) : [];

        $response = new HttpFoundation\JsonResponse(['error' => $errors]);

        return $response;
    }
}
