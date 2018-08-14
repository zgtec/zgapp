<?php

/**
 * GetView Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\GetView
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class GetView extends AbstractPlugin
{
    /**
     * Function __invoke
     *
     *
     *
     * @param bool $setTerminal
     * @param bool $errorCode
     * @param bool $template
     * @return ViewModel
     */
    public function __invoke($setTerminal = false, $errorCode = false, $template = false)
    {

        $controller = $this->getController();
        $view = new ViewModel($controller->view);
        if ($setTerminal)
            $view->setTerminal(true);
        if ($errorCode) {
            $controller->getResponse()->setStatusCode($errorCode);
            $view->setTemplate("error/" . $errorCode);
        }
        if ($template) {
            $view->setTemplate($template);
        }
        return $view;
    }

}