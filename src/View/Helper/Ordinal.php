<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Ordinal - returns ordinal word by number provided
 */

namespace ZgApp\View\Helper;

/**
 *
 * Class ZgApp\View\Helper\Ordinal
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Ordinal extends AbstractHelper
{
    protected $nums = array('zero', 'first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth');
    protected $snums = array('0', '1st', '2nd', '3d', '4th', '5th', '6th', '7th', '8th', '9th');

    /**
     * Function __invoke
     *
     *
     *
     * @param int $num
     * @param bool $short
     * @return mixed|string
     */
    public function __invoke($num = 1, $short = false)
    {
        if ($short) {
            $nums = $this->snums;
        } else {
            $nums = $this->nums;
        }
        return isset($nums[$num]) ? $nums[$num] : '';
    }

}
