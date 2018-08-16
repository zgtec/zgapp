<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

/**
 * GetView Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

/**
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