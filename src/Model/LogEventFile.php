<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Model;

use Zend\Log\Formatter\Simple as Formatter;
use Zend\Log\Writer\Stream as Writer;
use Zend\Log\Filter\Priority;

/**
 *
 * Class ZgApp\Model\LogEventFile
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class LogEventFile extends \Zend\Log\Logger
{
    public $format = '%timestamp%; %priorityName%; %priority%; %message%';

    /**
     * Function addWriterPath
     *
     *
     *
     * @param $path
     * @param bool $priority
     */
    public function addWriterPath($path, $priority = false)
    {
        $writer = new Writer($path);
        $writer->setFormatter(new Formatter($this->format));
        if ($priority) {
            $writer->addFilter(new Priority($priority));
        }
        $this->addWriter($writer);
    }


}
