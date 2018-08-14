<?php

/**
 * HeadTitle Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\headTitle
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class headTitle extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param $title
     * @param bool $force
     * @return null
     */
    public function __invoke($title, $force = false)
    {
        $controller = $this->getController();
        $renderer = $controller->getContainer()->get('Zend\View\Renderer\PhpRenderer');

        if ($force) {
            $renderer->headTitle($title);
        } elseif (!isset($controller->headTitlePlugin)) {
            $renderer->headTitle($title)->setSeparator(' - ')->setAutoEscape(false);
            if (isset($controller->headTitle)) {
                $renderer->headTitle($controller->headTitle);
            }
        } else {
            $renderer->headTitle($title);
        }

        return null;
    }

}
