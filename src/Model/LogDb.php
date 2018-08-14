<?php

namespace ZgApp\Model;

use Zend\Log\Writer\Db as Writer;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Model\LogDb
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class LogDb extends \Zend\Log\Logger
{

    public $columnMapping = array(
        'message' => 'message',
        'priority' => 'priority',
        'extra' => array(
            'event' => 'event',
            'status' => 'status',
            'userid' => 'userid',
            'ip' => 'ip',
            'comments' => 'comments'
        )
    );

    /**
     * Function addWriterPath
     *
     *
     *
     * @param $dbconfig
     * @param string $table
     */
    public function addWriterPath($dbconfig, $table = 'logs')
    {
        $writer = new Writer($dbconfig, $table, $this->columnMapping);
        $writer->setFormatter(new \Zend\Log\Formatter\Db('Y-m-d H:i:s'));
        $this->addWriter($writer);
    }
}
