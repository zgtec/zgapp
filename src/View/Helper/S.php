<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * S - returns ordinal word by number provided
 */

namespace ZgApp\View\Helper;

/**
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
class S extends AbstractHelper
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
