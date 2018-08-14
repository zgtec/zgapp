<?php

/**
 * ZgApp Regexp Filter
 *
 * @author vladimir
 */

namespace ZgApp\Filter;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Filter\Regexp
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Regexp
{
    public $xpattern = array(
        'url' => '/([^0-9.?#=&:a-z_\-\/])*/i',
        'google' => '/([^0-9. a-z])*/i',
        'email' => '/([^0-9.a-z_\-@\/])*/i',
        'code' => '/([^0-9a-z])*/i',
        'ccname' => '/([^a-z\-])*/i',
        'date' => '/([^0-9\/])*/i',
        'num' => '/([^0-9])*/i'
    );

    /**
     * Function filter
     *
     *
     *
     * @param $string
     * @param $pattern
     * @return mixed
     */
    public function filter($string, $pattern)
    {
        $filter = new \Zend\Filter\PregReplace($this->xpattern[$pattern], '');
        $string = $filter->filter($string);
        return $string;
    }


}