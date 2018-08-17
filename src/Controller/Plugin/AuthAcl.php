<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Auth Acl Plugin - uses ACL and AUTH services
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\AuthAcl
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class AuthAcl extends AbstractPlugin
{

    protected $controller = false;
    protected $user;

    /**
     * Function __invoke
     *
     *
     *
     * @return $this
     */
    public function __invoke()
    {
        $this->controller = $this->getController();
        $this->user = $this->controller->admin();
        return $this;
    }

    /**
     * Function setUserPermissions
     *
     *
     *
     * @param $role
     * @return bool
     */
    public function setUserPermissions($role)
    {
        if ($this->user->role != $role || $this->user->role=='guest') {
            return false;
        }

        $add = array();
        $permissions = array();

        if ($role == 'admin') {
            $permissionsSelect = $this->controller->mainconfig['acl']['adminPermissions'];
            $permissionsSelect += $this->controller->mainconfig['acl']['userPermissions'];
        } else {
            $permissionsSelect = $this->controller->mainconfig['acl'][$role . 'Permissions'];
        }

        if ($this->user->superuser) {
            foreach ($permissionsSelect as $pk => $ps) {
                $permissions[] = $pk;
            }
        } else {
            $userPermissions = explode("|", $this->user->permissions);
            foreach ($userPermissions as $up) {
                if (isset($permissionsSelect[$up])) {
                    $permissions[] = $up;
                }
            }
        }

        if (count($permissions) > 0) {
            foreach ($permissions as $p) {
                $p = explode(":", $p);
                $controller = $p[0];
                $actions = explode(",", $p[1]);
                foreach ($actions as $a) {
                    $add[$controller][] = $a;
                }
            }
        }

        if (count($add) > 0) {
            foreach ($add as $controller => $actions) {
                foreach ($actions as $a) {
                    $this->controller->mainconfig['acl']['permissions'][$role][$controller][] = $a;
                }
            }
        }
        return true;
    }

    /**
     * Function getAcl
     *
     *
     *
     * @return \ZgApp\Model\Acl
     */
    public function getAcl()
    {
        $this->setUserPermissions($this->user->role);
        $acl = new \ZgApp\Model\Acl($this->controller->mainconfig['acl']);
        return $acl;
    }


    /**
     * Function checkRouteAccess
     *
     *
     *
     * @return bool
     */
    public function checkRouteAccess()
    {
        $route = $this->controller->getEvent()->getRouteMatch();
        $acl = $this->getAcl();

        // Granting Access to Super User
        if ($this->user->role!='guest' && $this->user->superuser) {
            return true;
        }

        if (!$acl->hasResource($route->getParam('controller'))) {
            return false;
        }

        if ($acl->isAllowed($this->user->role, $route->getParam('controller'), $route->getParam('action'))) {
            return true;
        }
        return false;
    }

    /**
     * Function isValid
     *
     *
     *
     * @param $username
     * @param $password
     * @return mixed
     */
    public function isValid($username, $password)
    {
        return $this->controller->service('auth')->isValid($username, $password);
    }

    /**
     * Function clearIdentity
     *
     *
     *
     * @return mixed
     */
    public function clearIdentity()
    {
        return $this->controller->service('auth')->clearIdentity();
    }

    /**
     * Function preparePassword
     *
     *
     *
     * @param $password
     * @param $dynamicSalt
     * @return mixed
     */
    public function preparePassword($password, $dynamicSalt)
    {
        return $this->controller->service('auth')->preparePassword($password, $dynamicSalt);
    }


    /**
     * Function dynamicSalt
     *
     *
     *
     * @return mixed
     */
    public function dynamicSalt()
    {
        return $this->controller->service('auth')->dynamicSalt();
    }

    /**
     * Function generateHashes
     *
     *
     *
     * @param \ZgApp\Model\Db\User $admin
     * @return mixed
     */
    public function generateHashes(\ZgApp\Model\Db\User $admin)
    {
        return $this->controller->service('auth')->generateHashes($admin);
    }

    /**
     * Function checkHashes
     *
     *
     *
     * @param \ZgApp\Model\Db\User $admin
     * @param $password
     * @return mixed
     */
    public function checkHashes(\ZgApp\Model\Db\User $admin, $password)
    {
        return $this->controller->service('auth')->checkHashes($admin, $password);
    }

    /**
     * Function getHashesNum
     *
     *
     *
     * @return mixed
     */
    public function getHashesNum()
    {
        return $this->controller->service('auth')->getHashesNum();
    }

    /**
     * Function getLockCount
     *
     *
     *
     * @return mixed
     */
    public function getLockCount()
    {
        return $this->controller->service('auth')->getLockCount();
    }

    /**
     * Function getLockTime
     *
     *
     *
     * @return mixed
     */
    public function getLockTime()
    {
        return $this->controller->service('auth')->getLockTime();
    }

}