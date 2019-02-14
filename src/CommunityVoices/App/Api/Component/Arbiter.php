<?php

namespace CommunityVoices\App\Api\Component;

/**
 * @overview Assumes role of access controller by interpreting the rules and
 * arbitrating.
 *
 * The specific rules that the Arbiter reads and enacts are stored in
 * App/Api/Component/Config/AccessControlList.json
 *
 * @todo This needs more thought -- there needs to be higher security
 */

use CommunityVoices\Model\Entity;

class Arbiter
{
    protected $rules;

    protected $roles;

    public function __construct($roles, $rules)
    {
        $this->rules = $rules;
        $this->roles = $roles;


        /**
         * this is smelly
         */
        foreach ($this->roles as $key => $value) {
            $this->roles[$value] = $key;
        }
    }

    public function isAllowedForIdentity($signature, Entity\User $user)
    {
        $rule = $this->matchRuleBySignature($signature);

        if (!$rule) {
            return false;
        }

        $role = $user->getRole();

        $allowAbove = $this->roles[$rule[1]['allow']];

        return $role >= $allowAbove;
    }

    private function matchRuleBySignature($needle)
    {
        foreach ($this->rules as $key => $rule) {
            if ($rule[0] === $needle) {
                return $rule;
            }
        }

        foreach ($this->rules as $key => $rule) {
            if (fnmatch($rule[0], $needle, FNM_NOESCAPE)) {
                return $rule;
            }
        }

        return false;
    }
}
