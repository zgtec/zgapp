<?php

/**
 * Render Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Render
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Render extends AbstractPlugin
{

    /**
     * Function __invoke
     *
     *
     *
     * @param $template
     * @return mixed
     */
    public function __invoke($template)
    {
        $template = str_replace(".phtml", "", $template);
        $controller = $this->getController();
        $config = $this->getController()->getContainer()->get('config');

        $view = new ViewModel($controller->view);
        $view->setTemplate($template);

        $renderer = new PhpRenderer();
        $resolver = new Resolver\AggregateResolver();
        $map = new Resolver\TemplateMapResolver($config['view_manager']['template_map']);
        $stack = new Resolver\TemplatePathStack($config['view_manager']['template_path_stack']);
        $resolver->attach($map)->attach($stack);
        $renderer->setResolver($resolver);

        foreach ($config['view_helpers']['factories'] as $name => $helper) {
            $renderer->getHelperPluginManager()->setFactory($name, $helper);
        }

        $helperConfig = new \Zend\Form\View\HelperConfig();
        $helperConfig->configureServiceManager($renderer->getHelperPluginManager());

        return $renderer->render($view);
    }

}
