<?php

/**
 * ZgApp HideCard Filter
 *
 * @author vladimir
 */

namespace ZgApp\Filter;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Filter\HideCard
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class HideCard
{
    protected $pattern = '/(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})/';

    /**
     * Function filter
     *
     *
     *
     * @param $string
     * @return mixed
     */
    public function filter($string)
    {
        for ($i = 0; $i < 5; $i++) {
            $string = $this->cardReplace($string);
        }
        return $string;
    }

    /**
     * Function cardReplace
     *
     *
     *
     * @param $string
     * @return mixed
     */
    public function cardReplace($string)
    {
        $matches = array();
        preg_match($this->pattern, $string, $matches);

        foreach ($matches as $m) {
            $rm = str_pad(substr($m, -4), strlen($m), 'X', STR_PAD_LEFT);
            $string = str_replace($m, $rm, $string);
        }
        return $string;
    }


}