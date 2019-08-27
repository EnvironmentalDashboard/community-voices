<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;

class SecuredComponent
{
  private $identifier;
  private $arbiter;
  private $logger;

  public function __construct(
      Arbiter $arbiter,
      Contract\CanIdentify $identifier,
      \Psr\Log\LoggerInterface $logger
  ) {
      $this->arbiter = $arbiter;
      $this->identifier = $identifier;
      $this->logger = $logger;
  }

  // Automatically secures each called function in every API controller / view.
  public function __call($method, $arguments)
  {
    $user = $this->identifier->identify();
    $signature = get_class($this) . "::" . $method;

    if (!method_exists($this, $method)) {
        $this->logger->error('SecuredComponent MethodNotFound Exception', ['message' => 'Method not found ' . $signature]);
        throw new Exception\MethodNotFound('Method not found ' . $signature);
    }

    if (!$this->arbiter->isAllowedForIdentity($signature, $user)) {
        $this->logger->error('SecuredComponent AccessDenied Exception', ['message' => 'Access denied']);
        throw new Exception\AccessDenied($user);
    }

    return call_user_func_array([$this, $method], $arguments);
  }
}
