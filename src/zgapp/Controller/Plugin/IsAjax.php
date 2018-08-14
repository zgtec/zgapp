<?php

/**
 * IsAjax Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\IsAjax
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class IsAjax extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param bool $stop
     * @return mixed
     */
    public function __invoke($stop = false)
    {
        if (!$stop) {
            return $this->getController()->getRequest()->isXmlHttpRequest();
        }

        if (!$this->getController()->getRequest()->isXmlHttpRequest()) {
            die($this->getController()->render('zg-app/zg-app/errors/denied'));
        }
    }

}
