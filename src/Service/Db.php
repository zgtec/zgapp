<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
 *
 * Class ZgApp\Service\Db
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Db implements \Zend\ServiceManager\Factory\FactoryInterface
{

    protected $serviceManager;
    protected $controller;
    protected $key;
    protected $entity;

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return $this
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $this->serviceManager = $container;
        return $this;
    }

    /**
     * Function setController
     *
     *
     *
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;
        return $this;
    }

    /**
     * Function setKey
     *
     *
     *
     * @param $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * Function model
     *
     *
     *
     * @param $entity
     * @param bool $table
     * @return $this
     */
    public function model($entity, $table = false)
    {
        $this->entity = new $entity;
        if ($table) {
            $this->entity->setTable($table);
        }
        return $this;
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
        $this->entity->setTable($table);
        return $this;
    }

    /**
     * Function table
     *
     *
     *
     * @return \ZgApp\Model\Db\Table
     */
    public function table()
    {
        $table = new \ZgApp\Model\Db\Table($this->getAdapter());
        $table->init($this->entity);
        return $table;
    }

    /**
     * Function getAdapter
     *
     *
     *
     * @return mixed
     */
    public function getAdapter()
    {
        if ($this->key == 'db' || !strlen($this->key)) {
            $dbAdapter = $this->serviceManager->get('maindb');
        } else {
            $dbAdapter = $this->serviceManager->get($this->key);
        }
        return $dbAdapter;
    }

    /**
     * Function showTables
     *
     *
     *
     * @return array
     */
    public function showTables()
    {
        $results = $this->getAdapter()->createStatement("show tables")->execute();
        $tables = array();
        foreach ($results as $r) {
            foreach ($r as $k => $v) {
                $tables[] = $v;
            }
        }
        return $tables;
    }


    /**
     * Function fetchRows
     *
     *
     *
     * @param $orderby
     * @param bool $where
     * @param bool $limit
     * @param bool $offset
     * @return mixed
     */
    public function fetchRows($orderby, $where = false, $limit = false, $offset = false)
    {
        return $this->table()->fetchRows($orderby, $where, $limit, $offset);
    }

    /**
     * Function fetchForSelect
     *
     *
     *
     * @param string $key
     * @param string $value
     * @param string $orderby
     * @param bool $where
     * @param bool $limit
     * @param bool $offset
     * @return array
     */
    public function fetchForSelect($key = 'id', $value = 'name', $orderby = "id", $where = false, $limit = false, $offset = false)
    {
        $results = $this->fetchRows($orderby, $where, $limit, $offset);
        $out = array();
        $out[] = "Please select from list";
        foreach ($results as $r) {
            $out[$r->$key] = $r->$value;
        }
        return $out;
    }

    /**
     * Function countRows
     *
     *
     *
     * @param array $where
     * @return int
     */
    public function countRows($where = array())
    {
        return $this->table()->countRows($where);
    }

    /**
     * Function crudTable
     *
     *
     *
     * @param string $tableName
     * @param string $orderby
     * @param bool $where
     * @param bool $perPage
     * @param bool $skipCount
     * @return array
     */
    public function crudTable($params)
    {
        // Setting Offset
        $offset = ($params->page - 1) * $params->perpage;
        if ($offset < 0) {
            $offset = 0;
        }

        if (!is_array($params->orderby) && !in_array($params->orderby,$this->entity->getColumns())) {
            $params->orderby = 'id';
        }

        if (is_array($params->orderby)) {
            $orderby = $params->orderby;
        } elseif (isset($params->orderdir[$params->orderby])) {
            $orderby = $params->orderby . ' ' . $params->orderdir[$params->orderby];
        } else {
            $orderby = $params->orderby . ' asc';
        }


        $results = $this->fetchRows($orderby, $params->where, $params->perpage, $offset);
        $count = $results->count();
        $total = $this->countRows($params->where);

        if ($count < 1 && $params->page > 1) {
            return "RESET";
        }

        return [
            "tableName" => '',
            "results" => $results,
            "count" => $count,
            "total" => $total,
            "page" => $params->page,
            "pages" => ceil($total / $params->perpage),
            "start" => $offset + 1,
            "end" => $offset + $count,
            "url" => $params->link . "/",
            "orderby" => is_array($params->orderby) ? '' : $params->orderby,
            "orderdir" => (!is_array($params->orderby) && isset($params->orderdir[$params->orderby]))?$params->orderdir[$params->orderby]:'asc',
        ];
    }

    /**
     * Function save
     *
     *
     *
     * @param null $data
     * @return bool
     * @throws \Exception
     */
    public function save($data = null)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }
        if ($data['id'] < 1) {
            unset($data['id']);
        }
        if (is_array($data)) {
            $this->entity->exchangeArray($data);
        }
        if (count($this->entity->getArrayCopy()) > 1)
            return $this->table()->saveRow();
        else return false;
    }

    /**
     * Function insert
     *
     *
     *
     * @param null $data
     * @return mixed
     */
    public function insert($data = null)
    {
        if (is_object($data)) {
            $data = (array)$data;
        }
        if (is_array($data)) {
            $this->entity->exchangeArray($data);
        }
        return $this->table()->insertRow();
    }

    /**
     * Function find
     *
     *
     *
     * @param $where
     * @return bool
     */
    public function find($where)
    {
        if (is_array($where)) {
            return $this->table()->fetchRow($where);
        } else {
            return $this->table()->getRow($where);
        }
    }

    /**
     * Function delete
     *
     *
     *
     * @param $where
     */
    public function delete($where)
    {
        if (is_array($where)) {
            return $this->table()->deleteRows($where);
        } else {
            return $this->table()->deleteRow($where);
        }
    }

}
