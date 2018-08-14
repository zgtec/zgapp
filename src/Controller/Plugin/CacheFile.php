<?php

/**
 * Cache Class
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\CacheFile
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class CacheFile extends AbstractPlugin
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
        if (!isset($controller->zgCacherFile))
            $controller->zgCacherFile = $controller->service('cacheFile');
        return $controller->zgCacherFile;
    }

}

