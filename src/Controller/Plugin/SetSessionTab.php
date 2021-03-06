<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * SetSessionTab Plugin
 *
 * @author vladimir
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 *
 * Class ZgApp\Controller\Plugin\SetSessionTab
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class SetSessionTab extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param array $exclude
     */
    public function __invoke($exclude = array())
    {
        $controller = $this->getController();

        $sessionTab = $controller->getRequest()->getQuery('sessiontab');
        $sessionTab = $controller->escapeHtml($sessionTab);
        if (!strlen($sessionTab)) {
            $sessionTab = substr(base64_encode(microtime(true) * 10000), 0, -1);
        }
        $controller->sessionTab = $controller->layout()->sessionTab = $sessionTab;
        $controller->sessionTabExclude = $exclude;
    }

}
