<?php

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
