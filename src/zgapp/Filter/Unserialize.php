<?php

/**
 * Safe Unserialize Filter Class
 */

namespace ZgApp\Filter;

use Zend\Filter\AbstractFilter;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Filter\Unserialize
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Unserialize extends AbstractFilter
{

    /**
     * Function filter
     *
     *
     *
     * @param $val
     * @return bool|mixed
     */
    public function filter($val)
    {
        if (!is_array($val) && preg_match('/^a:[0-9]+:{/', $val) && !preg_match('/(^|;|{|})O:\+?[0-9]+:"/', $val)) {
            return unserialize($val);
        }
        return false;
    }

}
