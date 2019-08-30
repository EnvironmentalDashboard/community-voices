<?php

namespace CommunityVoices\Model\Service;

use Palladium;
use CommunityVoices\Model\Entity;
use CommunityVoices\Model\Component;
use CommunityVoices\Model\Mapper;
use CommunityVoices\Model\Exception;

class UserManagement extends Management
{
    private $mapperFactory;
    private $stateObserver;

    const FORM_ATTRIBUTES = [
        'role'
    ];

    /**
     * @param MapperFactory $mapperFactory
     * @param StateObserver $stateObserver
     */
    public function __construct(
        Component\MapperFactory $mapperFactory,
        Component\StateObserver $stateObserver
    ) {
        $this->mapperFactory = $mapperFactory;
        $this->stateObserver = $stateObserver;
    }

    // Considering this was copied from QuoteManagement, this could be patterned too.
    public function update(
        $id,
        array $attributes,
        $identity = null
    ) {
        $userMapper = $this->mapperFactory->createDataMapper(Mapper\User::class);

        /*
         * Create Quote entity and set attributes
         */

        $user = new Entity\User;

        // Since this function will either upload or update, we will pick what to do
        // on if $id has a value or not.
        $user->setId((int) $id);
        $userMapper->fetch($user);

        /*
         * Using an array of attributes allows
         * us to only change specific attributes
         * that we deem appropriate.
         * Anything without a value in the array
         * will not be changed.
         */
        $this->setEntityAttributes($user, $attributes, self::FORM_ATTRIBUTES);

        /*
         * Create error observer w/ appropriate subject and pass to validator
         */

        $this->stateObserver->setSubject('userFormErrors');
        $isValid = $user->validateForSave($this->stateObserver);

        $clientState = $this->mapperFactory->createClientStateMapper(Mapper\ClientState::class);

        /*
         * If there are any errors at this point, save the error state and stop
         * the registration process
         */

        if ($this->stateObserver->hasEntries()) {
            $clientState->save($this->stateObserver);
            return false;
        }

        $userMapper->save($user);

        return true;
    }
}
