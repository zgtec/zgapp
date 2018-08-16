<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\Service;

use Interop\Container\ContainerInterface;

/**
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
