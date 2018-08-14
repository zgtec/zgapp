<?php

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
 * Copyright (c) 2010-2018 ZGTec Inc.
 * All rights reserved.
 *
 * Class ZgApp\Service\CacheFile
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class CacheFile implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \Zend\Cache\Storage\Adapter\Filesystem
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $cacher = new \Zend\Cache\Storage\Adapter\Filesystem();
        $cacher->addPlugin(new \Zend\Cache\Storage\Plugin\Serializer());
        $cacher->addPlugin(new \Zend\Cache\Storage\Plugin\IgnoreUserAbort());
        $cacher->addPlugin(new \Zend\Cache\Storage\Plugin\OptimizeByFactor());
        $cacher->setOptions(array('cache_dir' => $config['cacheFile']['save_path'], 'ttl' => $config['cacheFile']['ttl']));
        return $cacher;
    }

}
