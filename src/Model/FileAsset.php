<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

/**
 * ZgApp File Asset Class
 */
class FileAsset
{

    /**
     * getArrayCopy() - returns object variables as array
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
     * setArrayCopy() - setting object properties from data array
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
