<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model\Db;

/**
 *
 * Class ZgApp\Model\Db\Entity
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Entity
{

    protected $dbtable;
    public $resultsCount;

    /**
     * Entity constructor.
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        if (is_array($options)) {
            $this->exchangeArray($options);
        }
    }

    /**
     * Function exchangeArray
     *
     *
     *
     * @param $data
     */
    public function exchangeArray($data)
    {
        foreach ($data as $key => $val) {
            if (!is_null($val) && property_exists($this, $key)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * Function setTable
     *
     *
     *
     * @param $table
     * @return $this
     */
    public function setTable($table)
    {
        $this->dbtable = $table;
        return $this;
    }

    public function getTable()
    {
        return $this->dbtable;
    }

    /**
     * Function getColumns
     *
     *
     *
     * @return array
     */
    public function getColumns()
    {
        return array();
    }

    /**
     * Function getArrayCopy
     *
     *
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
            if (in_array($k, $this->getColumns()))
                $out[$k] = $v;
        }
        return $out;
    }

}
