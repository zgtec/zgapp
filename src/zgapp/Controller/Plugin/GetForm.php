<?php

/**
 * Form Caller Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\GetForm
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class GetForm extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param $name
     * @return bool
     */
    public function __invoke($name)
    {
        $controller = $this->getController();
        $route = $controller->getEvent()->getRouteMatch();
        $module = dirname(dirname(str_replace("\\", "/", $route->getParam('controller'))));
        if (class_exists($name)) {
            $formClass = $name;
        } else {
            $formClass = '\\' . $module . '\\' . 'Form' . '\\' . $name;
        }

        if (!class_exists($formClass))
            return false;

        return new $formClass($controller);
    }

}