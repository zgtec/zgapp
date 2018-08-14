<?php

/**
 * Helper Caller Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Helper
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Helper extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $name
     * @param array $options
     * @return mixed
     */
    public function __invoke($name, $options = array())
    {
        $controller = $this->getController();
        $viewHelperManager = $controller->getContainer()->get('ViewHelperManager');
        return $viewHelperManager->get($name);
    }

}