<?php

namespace ZgApp\Controller\Factory;


use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Controller\Factory\AbstractFactory
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class AbstractFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return mixed
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $className = "\\" . $requestedName . "Controller";
        $controller = new $className();

        if (method_exists($controller, 'setContainer')) {
            $controller->setContainer($container);
        }

        return $controller;
    }

}