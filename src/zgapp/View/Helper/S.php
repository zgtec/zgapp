<?php

/**
 * S - returns ordinal word by number provided
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\S
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class S extends \ZgApp\View\Helper\AbstractHelper
{
    /**
     * Function __invoke
     *
     *
     *
     * @param int $num
     * @return string
     */
    public function __invoke($num = 0)
    {
        return ($num > 1) ? "s" : "";
    }

}
