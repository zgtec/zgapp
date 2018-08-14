<?php

/**
 * Filter Regexp helper
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\Regexp
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Regexp extends \ZgApp\View\Helper\AbstractHelper
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $value
     * @param $pattern
     * @return mixed
     */
    public function __invoke($value, $pattern)
    {
        $filter = new \Core\Filter\Regexp();
        return $filter->filter($value, $pattern);
    }

}