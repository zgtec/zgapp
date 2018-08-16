<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Db Class
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

/**
 *
 * Class ZgApp\Controller\Plugin\Db
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Db extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param string $key
     * @return mixed
     */
    public function __invoke($key = 'db')
    {
        $controller = $this->getController();
        $controller->zgDb = $controller->getContainer()->get('db')->setController($controller)->setKey($key);

        return $controller->zgDb;
    }

}

