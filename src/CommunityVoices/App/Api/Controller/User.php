<?php

namespace CommunityVoices\App\Api\Controller;

use CommunityVoices\Model\Service;
use CommunityVoices\Model\Exception;
use CommunityVoices\App\Api\Component;

class User extends Component\Controller
{
    protected $registrationService;
    protected $userLookup;
    protected $userManagement;

    public function __construct(
        Service\Registration $registrationService,
        Service\UserLookup $userLookup //,
        // Service\UserManagement $userManagement
    ) {
        $this->registrationService = $registrationService;
        $this->userLookup = $userLookup;
        //$this->userLookup = $userManagement;
    }

    /**
     * User registration
     * @return bool A boolean of if the User was created successfully or not.
     */
    public function postUser($request)
    {
        $email = $request->request->get('email');
        $password = $request->request->get('password');
        $confirmPassword = $request->request->get('confirmPassword');
        $firstName = $request->request->get('firstName');
        $lastName = $request->request->get('lastName');

        // This may very well be '', but the service checks for the token
        // being empty.
        $token = (string) $request->request->get('token');

        return $this->registrationService->createUser(
            $email,
            $password,
            $confirmPassword,
            $firstName,
            $lastName,
            $token
        );
    }

    public function getUser($request)
    {
        $userId = (int) $request->attributes->get('id');

        try {
            $this->userLookup->findById($userId);
        } catch (Exception\IdentityNotFound $e) {
            $this->send404();
        }
    }

    public function newToken($request)
    {
        $email = $request->request->get('email');
        $role = (int) $request->request->get('role');
        $token = $this->random_str(16);

        $this->registrationService->insertToken($email, $role, $token);
        // we should maybe just return the token and do this somewhere else:
        $this->registrationService->sendInviteEmail($email, $role, $token);
    }

    /**
     * Generate a random string, using a cryptographically secure
     * pseudorandom number generator (random_int)
     *
     * For PHP 7, random_int is a PHP core function
     * For PHP 5.x, depends on https://github.com/paragonie/random_compat
     *
     * @param int $length      How many characters do we want?
     * @param string $keyspace A string of all possible characters
     *                         to select from
     * @return string
     */
    private function random_str($length, $keyspace = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ')
    {
        $pieces = [];
        $max = mb_strlen($keyspace, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            $pieces []= $keyspace[random_int(0, $max)];
        }
        return implode('', $pieces);
    }
}
