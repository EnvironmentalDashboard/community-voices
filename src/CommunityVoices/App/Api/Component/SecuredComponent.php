<?php

namespace CommunityVoices\App\Api\Component;

use CommunityVoices\App\Api\AccessControl;
use CommunityVoices\App\Api\Component;
use CommunityVoices\Model\Component\StateObserver;
use CommunityVoices\Model\Entity;

class SecuredComponent
{
  private $identifier;
  private $logger;
  private $stateObserver;

  const ACCESS_CONTROL_NAMESPACE = 'CommunityVoices\App\Api\AccessControl\\';

  public function __construct(
      Contract\CanIdentify $identifier,
      \Psr\Log\LoggerInterface $logger,
      StateObserver $stateObserver
  ) {
      $this->identifier = $identifier;
      $this->logger = $logger;
      $this->stateObserver = $stateObserver;
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
        if (!call_user_func_array([$accessControlClass, $method], [$user, $arguments, $this->stateObserver])) {
            $this->accessDenied($user);
        } else {
            // We do a double pass in order to allow all data to be loaded so that we
            // can do processing on it.
            // This is dangerous for actions like deletion where the actual method
            // trying to be done has side-effects.
            // It would be better to provide some sort of interface to only retrieving
            // the data that is needed for the true access control value to be reached,
            // and only get the necessary data.
            // Maybe a predetermined suffix like "AccessControl" (so "getQuoteAccessControl")?
            // Can be implemented later because it would simply require changing the lines
            // below.
            $return = call_user_func_array([$this, $method], $arguments);

            if (!call_user_func_array([$accessControlClass, $method], [$user, $arguments, $this->stateObserver])) {
                $this->accessDenied($user);
            } else {
                return $return;
            }
        }
    } else {
        // this should be removed once this feature is shipped, but it is
        // incredibly helpful for debugging
        var_dump('did not find ' . $accessControlClass . '::' . $method);
        die();
        $this->accessDenied($user);
    }
  }

  private function accessDenied($user)
  {
      $this->logger->error('SecuredComponent AccessDenied Exception', ['message' => 'Access denied']);
      throw new Exception\AccessDenied($user);
  }
}
