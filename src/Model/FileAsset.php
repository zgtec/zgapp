<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

/**
 *
 * Class ZgApp\Model\FileAsset
 *
 *
 *
 * @project     ZgApp 3 module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class FileAsset
{

    /**
     * Function getArrayCopy
     *
     * returns object variables as array
     *
     * @param bool $allowNull
     * @return array
     */
    public function getArrayCopy($allowNull = false)
    {
        $vars = get_object_vars($this);
        $out = array();
        foreach ($vars as $k => $v) {
            if (is_null($v) && !$allowNull)
                continue;
            $out[$k] = $v;
        }
        return $out;
    }

    /**
     * Function setArrayCopy
     *
     *  setting object properties from data array
     *
     * @param $data
     */
    public function setArrayCopy($data)
    {
        foreach ($data as $key => $val) {
            if (property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }

}
