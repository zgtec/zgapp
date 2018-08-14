<?php

namespace ZgApp\Model;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\Arrays
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Arrays
{
    /**
     * Function setData
     *
     *
     *
     * @param $var
     * @param array $keys
     * @param null $default
     * @return null
     */
    static public function setData($var, array $keys, $default = null)
    {
        foreach ($keys as $key) {
            if (isset($var[$key]))
                $var = $var[$key];
            else
                return $default;
        }

        return $var;
    }

    /**
     * Function multisort
     *
     *
     *
     * @param $array
     * @param $key
     * @return array
     */
    static function multisort($array, $key)
    {
        $valsort = array();
        $ret = array();
        reset($array);
        foreach ($array as $ii => $va) {
            if (is_object($va)) {
                $valsort[$ii] = $va->$key;
            } else {
                $valsort[$ii] = $va[$key];
            }
        }
        asort($valsort);
        foreach ($valsort as $ii => $va) {
            $ret[] = $array[$ii];
        }
        return $ret;
    }


}
