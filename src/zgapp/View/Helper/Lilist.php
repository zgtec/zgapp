<?php

/**
 * Lilist helper - lists array items as list of ul
 */

namespace ZgApp\View\Helper;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\View\Helper\Lilist
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Lilist extends AbstractHelper
{

    /**
     * Function __invoke
     *
     *
     *
     * @param array $items
     * @param string $type
     * @return string
     */
    public function __invoke(array $items, $type = 'all')
    {
        $filter = new \ZgApp\Filter\Html();
        $out = '';
        $i = 1;
        foreach ($items as $item) {
            if (strlen($item) &&
                ($type == 'all' ||
                    ($type == 'even' && $i % 2 == 1) ||
                    ($type == 'odd' && $i % 2 == 0))) {
                $out .= "<li>" . $filter->filter($item) . "</li>";
            }
            $i++;
        }
        return $out;
    }

}
