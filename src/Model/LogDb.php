<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

use Zend\Log\Writer\Db as Writer;
use Zend\Log\Filter\Priority;

/**
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
    public function addWriterPath($dbconfig, $table = 'logs',  $priority = false)
    {
        $writer = new Writer($dbconfig, $table, $this->columnMapping);
        $writer->setFormatter(new \Zend\Log\Formatter\Db('Y-m-d H:i:s'));
        if ($priority) {
            $writer->addFilter(new Priority($priority));
        }
        $this->addWriter($writer);
    }
}
