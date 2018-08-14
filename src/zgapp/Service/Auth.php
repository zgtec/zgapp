<?php

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Service\Auth
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class Auth implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \ZgApp\Model\Auth
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $model = new \ZgApp\Model\Auth();
        $model->setStaticSalt($config['auth']['staticSalt']);
        $model->setDbAdapter($container->get('maindb'), new \ZgApp\Model\Db\User());
        return $model;
    }

}

