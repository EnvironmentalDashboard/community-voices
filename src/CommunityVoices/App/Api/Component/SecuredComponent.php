<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\Component;
use CommunityVoices\Model\Entity;

class SecuredComponent
{
  private $identifier;
  private $logger;

  // Since our standard is camelCase, no normal function names will start with 'CAN'
  const ACCESS_CONTROL_PREFIX = 'CAN';

  public function __construct(
      Contract\CanIdentify $identifier,
      \Psr\Log\LoggerInterface $logger
  ) {
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

    // In future, may need to create one class for all these rules, as
    // XSLT cannot access it currently.
    $accessControlMethod = 'CAN' . $method;

    if (method_exists($this, $accessControlMethod)) {
        if (!$this->{$accessControlMethod}($user, $arguments)) {
            $this->accessDenied($user);
        }
    } else {
        var_dump('did not find ' . $accessControlMethod);
        die();
        $this->accessDenied($user);
    }

    return call_user_func_array([$this, $method], $arguments);
  }

  private function accessDenied($user)
  {
      $this->logger->error('SecuredComponent AccessDenied Exception', ['message' => 'Access denied']);
      throw new Exception\AccessDenied($user);
  }
}
