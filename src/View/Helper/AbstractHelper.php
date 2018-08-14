<?php

/**
 * Abstract helper with service locator
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\AbstractHelper
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class AbstractHelper extends \Zend\View\Helper\AbstractHelper
{
    protected $container;

    /**
     * Function setContainer
     *
     *
     *
     * @param $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

}