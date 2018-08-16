<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Abstract helper with service locator
 */

namespace ZgApp\View\Helper;

/**
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