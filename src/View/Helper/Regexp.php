<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Filter Regexp helper
 */

namespace ZgApp\View\Helper;

/**
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
class Regexp extends AbstractHelper
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