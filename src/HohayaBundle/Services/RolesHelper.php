<?php

namespace HohayaBundle\Services;

/**
 * Roles helper displays roles set in config.
 */
class RolesHelper
{
    private $rolesHierarchy;

    public function __construct($rolesHierarchy)
    {
        $this->rolesHierarchy = $rolesHierarchy;
    }

    /**
     * Return roles.
     *
     * @return array
     */
    public function getRoles()
    {
        $roles = array();

        foreach ($this->rolesHierarchy as $key => $val) {
            $roles[$key] = $key;
        }

//        array_walk_recursive($this->rolesHierarchy, function($val,$key) use (&$roles) {
//            $roles[$val] = $key;
//        });

        return array_unique($roles);
    }
}