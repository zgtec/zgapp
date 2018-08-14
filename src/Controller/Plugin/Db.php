<?php

/**
 * Db Class
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
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

