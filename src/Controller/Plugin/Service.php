<?php

/**
 * Service Caller Plugin
 */

namespace ZgApp\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Plugin\Service
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Service extends AbstractPlugin
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
        $service = $controller->getContainer()->get($name);

        if (\method_exists($service, 'setController'))
            $service->setController($controller);
        return $service;
    }

}