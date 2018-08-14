<?php

namespace ZgApp\Model\Db;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Expression;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\Db\Table
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Table extends AbstractTableGateway
{

    protected $table;
    protected $entity;

    /**
     * Table constructor.
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Function init
     *
     *
     *
     * @param $entity
     * @return $this
     */
    public function init($entity)
    {
        $this->entity = $entity;
        $this->table = $entity->getTable();
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype($entity);
        $this->initialize();
        return $this;
    }

    /**
     * Function expression
     *
     *
     *
     * @param $in
     * @return Expression
     */
    public function expression($in)
    {
        $expression = new \Zend\Db\Sql\Expression();
        $expression->setExpression($in);
        return $expression;
    }

    /**
     * Function getSelect
     *
     *
     *
     * @return Select
     */
    public function getSelect()
    {
        $select = new Select();
        $select->from($this->table);
        //$select->getSqlString();
        return $select;
    }

    /**
     * Function fetchSelect
     *
     *
     *
     * @param $select
     * @return mixed
     */
    public function fetchSelect($select)
    {
        $resultSet = $this->selectWith($select);
        return $resultSet;
    }

    /**
     * Function makeJoin
     *
     *
     *
     * @param $select
     * @param $join
     * @return mixed
     */
    public function makeJoin($select, $join)
    {
        foreach ($join as $j) {
            $name = (isset($j['name'])) ? $j['name'] : null;
            $on = (isset($j['on'])) ? $j['on'] : null;
            $columns = (isset($j['columns'])) ? $j['columns'] : "*";
            $type = (isset($j['type'])) ? $j['type'] : "left";
            $select->join($name, $on, $columns, $type);
        }
        //echo $select->getSqlString(); exit;
        return $select;
    }

    /**
     * Function countRows
     *
     *
     *
     * @param bool $where
     * @return int
     */
    public function countRows($where = false)
    {
        $select = $this->getSelect();
        if ($where) {
            if (isset($where['join'])) {
                $select = $this->makeJoin($select, $where['join']);
                unset($where['join']);
            }
            if (isset($where['groupby'])) {
                $select->group($where['groupby']);
                unset($where['groupby']);
            }
            $select->where($where);
        }
        //echo $select->getSqlString(); echo "<br/>";
        $select->columns(array('resultsCount' => new Expression('COUNT(*)')));
        $results = $this->selectWith($select);
        $row = $results->current();
        return (int)$row->resultsCount;
    }

    /**
     * Function fetchRows
     *
     *
     *
     * @param string $orderby
     * @param bool $where
     * @param bool $limit
     * @param bool $offset
     * @return mixed
     */
    public function fetchRows($orderby = "id", $where = false, $limit = false, $offset = false)
    {
        $select = $this->getSelect();
        if ($where) {
            if (isset($where['join'])) {
                $select = $this->makeJoin($select, $where['join']);
                unset($where['join']);
            }
            if (isset($where['groupby'])) {
                $select->group($where['groupby']);
                unset($where['groupby']);
            }
            $select->where($where);

        }
        if ($limit)
            $select->limit($limit);
        if ($offset)
            $select->offset($offset);
        $select->order($orderby);
        return $this->fetchSelect($select);
    }

    /**
     * Function getRow
     *
     *
     *
     * @param $id
     * @return bool
     */
    public function getRow($id)
    {
        $id = (int)$id;
        $rowset = $this->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            return false;
        }
        return $row;
    }

    /**
     * Function fetchRow
     *
     *
     *
     * @param $where
     * @return bool
     */
    public function fetchRow($where)
    {
        $select = $this->getSelect();
        if ($where) {
            if (isset($where['select_columns'])) {
                $isColumns = true;
                $select->columns($where['select_columns']);
                unset($where['select_columns']);
            }
            $select->where($where);
        }

        $row = $this->fetchSelect($select)->current();

        if (!$row) {
            return false;
        }
        return $row;
    }

    /**
     * Function saveRow
     *
     *
     *
     * @return mixed
     * @throws \Exception
     */
    public function saveRow()
    {
        $data = array();
        foreach ($this->entity->getArrayCopy() as $key => $val) {
            if (!is_null($this->entity->$key) && !is_array($this->entity->$key) && !is_object($this->entity->$key)) {
                $data[$key] = stripslashes($val);
            } elseif (is_object($this->entity->$key)) {
                $data[$key] = $val;
            }
        }
        $id = (int)$this->entity->id;
        if ($id == 0) {
            $this->insert($data);
            if (property_exists($this->entity, "orderid"))
                $this->update(array("orderid" => $this->lastInsertValue), array("id" => $this->lastInsertValue));
        } else {
            if ($this->getRow($id)) {
                $this->update($data, array('id' => $id));
            } else {
                throw new \Exception('Row ' . $id . ' does not exist in ' . $this->table);
            }
        }
        return $this->lastInsertValue;
    }

    /**
     * Function insertRow
     *
     *
     *
     * @return mixed
     */
    public function insertRow()
    {
        $data = array();
        foreach ($this->entity->getArrayCopy() as $key => $val) {
            if (!is_null($this->entity->$key) && !is_array($this->entity->$key) && !is_object($this->entity->$key)) {
                $data[$key] = stripslashes($val);
            } elseif (is_object($this->entity->$key)) {
                $data[$key] = $val;
            }
        }

        $this->insert($data);
        if (property_exists($this->entity, "orderid"))
            $this->update(array("orderid" => $this->lastInsertValue), array("id" => $this->lastInsertValue));
        return $this->lastInsertValue;
    }

    /**
     * Function deleteRow
     *
     *
     *
     * @param $id
     */
    public function deleteRow($id)
    {
        $this->delete(array('id' => $id));
    }

    /**
     * Function deleteRows
     *
     *
     *
     * @param $where
     */
    public function deleteRows($where)
    {
        $this->delete($where);
    }

    /**
     * Function reorder
     *
     *
     *
     * @param $id
     * @param $direction
     * @param string $orderby
     * @param bool $where
     * @return bool
     * @throws \Exception
     */
    public function reorder($id, $direction, $orderby = "id", $where = false)
    {
        $id = (int)$id;
        if ($id === 0)
            return false;
        $rows = $this->fetchRows($orderby, $where);

        $break = false;
        $up = array();
        $down = array();
        $previous = array();
        $current = array();
        foreach ($rows as $row) {
            if (empty($previous)) {
                $first['id'] = $row->id;
                $first['orderid'] = $row->orderid;
            }
            if ($setdown) {
                $down['id'] = $row->id;
                $down['orderid'] = $row->orderid;
                $setdown = false;
            }
            if ($row->id === $id) {
                $up = (!empty($previous)) ? $previous : array();
                $current['id'] = $row->id;
                $current['orderid'] = $row->orderid;
                $setdown = true;
            }
            $previous['id'] = $row->id;
            $previous['orderid'] = $row->orderid;
        }
        if (empty($up))
            $up = $previous;
        if (empty($down))
            $down = $first;

        if ($direction === "up") {
            $this->saveRow(array("id" => $current['id'], "orderid" => $up['orderid']));
            $this->saveRow(array("id" => $up['id'], "orderid" => $current['orderid']));
        } elseif ($direction === "down") {
            $this->saveRow(array("id" => $current['id'], "orderid" => $down['orderid']));
            $this->saveRow(array("id" => $down['id'], "orderid" => $current['orderid']));
        }
    }

}
