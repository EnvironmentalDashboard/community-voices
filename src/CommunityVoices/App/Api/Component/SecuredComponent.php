<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\AccessControl;
use CommunityVoices\App\Api\Component;
use CommunityVoices\Model\Entity;

class SecuredComponent
{
  private $identifier;
  private $logger;

  const ACCESS_CONTROL_NAMESPACE = 'CommunityVoices\App\Api\AccessControl\\';

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

    $accessControlClass = self::ACCESS_CONTROL_NAMESPACE . ((new \ReflectionClass($this))->getShortName());

    if (method_exists($accessControlClass, $method)) {
        if (!call_user_func_array([$accessControlClass, $method], [$user, $arguments])) {
            $this->accessDenied($user);
        }
    } else {
        // this should be removed once this feature is shipped, but it is
        // incredibly helpful for debugging
        var_dump('did not find ' . $accessControlClass . '::' . $method);
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
