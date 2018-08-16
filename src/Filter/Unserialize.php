<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * Safe Unserialize Filter Class
 */

namespace ZgApp\Filter;

use Zend\Filter\AbstractFilter;

/**
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
