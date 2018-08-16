<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

use Zend\Permissions\Acl\Acl as Zend_Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

/**
 *
 * Class ZgApp\Model\Acl
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Acl extends Zend_Acl
{

    /**
     * Acl constructor.
     *
     * @param $config
     */
    public function __construct($config)
    {
        // Setting Up Roles
        foreach ($config['roles'] as $role => $parent) {
            if (strlen($parent))
                $this->addRole(new Role($role), $parent);
            else
                $this->addRole(new Role($role));
        }

        // Setting Up Resources
        foreach ($config['resources'] as $resource) {
            $this->addResource(new Resource($resource));
        }

        // Deny all for all
        $this->deny();

        // Setting Up Permissions
        foreach ($config['permissions'] as $role => $permissions) {
            foreach ($permissions as $resource => $objects) {
                $this->allow($role, $resource, $objects);
            }
        }
    }

    /**
     * Function isAllowed
     *
     *
     *
     * @param null $role
     * @param null $resource
     * @param null $privilege
     * @return bool
     */
    public function isAllowed($role = null, $resource = null, $privilege = null)
    {
        if (is_null($role)) {
            $role = 'guest';
        }

        if (is_array($privilege)) {
            foreach ($privilege as $p) {
                if (parent::isAllowed($role, $resource, $p)) {
                    return true;
                }
            }
            return false;
        } else {
            return parent::isAllowed($role, $resource, $privilege);
        }

    }

}