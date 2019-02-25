<?php

namespace CommunityVoices\App\Api\Component;

/**
 * @overview Assumes role of access controller by interpreting the rules and
 * arbitrating.
 *
 * The specific rules that the Arbiter reads and enacts are stored in
 * App/Api/Component/Config/AccessControlList.json
 *
 * For rules:
 * - Any specific rule takes priority over a pattern matches
 * - Pattern matches use UNIX filename patterns (see man for `fnmatch`)
 *
 * @todo This needs more thought -- there needs to be higher security
 * @todo Potentially deprecate and find a good object-oriented RBAC package
 */

use CommunityVoices\Model\Entity;

class Arbiter
{
    protected $rules;

    protected $roles;

    /**
     * Creates an arbiter based on the provided rules and roles
     *
     * @param array $roles
     * @param array $rules
     */
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

    /**
     * Checks if the provided $signature is allowed for $user
     *
     * @param string $signature Class signature as provided by `get_class`
     * @param User $user Valid pre-mapped user entity
     * @return boolean True if the user is not denied access to the provided signature
     */
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
        /**
         * <Priority!> Check specific rules
         */
        foreach ($this->rules as $key => $rule) {
            if ($rule[0] === $needle) {
                return $rule;
            }
        }

        /**
         * Check pattern-based rules
         */
        foreach ($this->rules as $key => $rule) {
            if (fnmatch($rule[0], $needle, FNM_NOESCAPE)) {
                return $rule;
            }
        }

        return false;
    }
}
