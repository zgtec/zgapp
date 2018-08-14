<?php

/**
 * EscapeHtml Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\EscapeHtml
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class EscapeHtml extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $value
     * @return mixed|string
     */
    public function __invoke($value)
    {
        $filter = new \ZgApp\Filter\Html();
        return $filter->filter($value);
    }

}
