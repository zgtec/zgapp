<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Authorized User Plugin - requires AUTH service run
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\Admin
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Admin extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @return mixed
     */
    public function __invoke()
    {
        $controller = $this->getController();

        $user = $controller->getContainer()->get("auth")->getIdentity();
        if (isset($user->id) && $user->id>0) {
            $current = $controller->db()->model('Admin\Model\Db\User')->find($user->id);
            return $current;
        } else {
            return $user;
        }
    }

}