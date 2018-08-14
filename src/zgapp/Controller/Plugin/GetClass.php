<?php

/**
 * Class Caller Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\GetClass
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class GetClass extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $name
     * @return mixed
     */
    public function __invoke($name)
    {
        return new $name;
    }

}