<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Filter HTML helper - allows services to be used in view
 */

namespace ZgApp\View\Helper;

/**
 *
 * Class ZgApp\View\Helper\Purify
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Purify extends AbstractHelper
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