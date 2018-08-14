<?php

/**
 * SetPageMessage Controller Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\SetPageMessage
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class SetPageMessage extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $message
     * @return $this
     */
    public function __invoke($message)
    {
        $controller = $this->getController();
        if (!$controller->layout()->pagemessage)
            $controller->layout()->pagemessage = 'ALERT##';
        if ($message)
            $controller->layout()->pagemessage .= "<p>" . $message . '</p>';
        return $this;
    }

}
