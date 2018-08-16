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
 * Class ZgApp\Service\LogDb
 *
 *
 *
 * @project     zgapp module
 * @author      Vladimir Dubina <vladimir@zgtec.com>
 * @link        https://github.com/zgtec/zgapp
 * @since       File available since Release 1.0
 */
class LogDb implements \Zend\ServiceManager\Factory\FactoryInterface
{

    /**
     * Function __invoke
     *
     *
     *
     * @param ContainerInterface $container
     * @param $requestedName
     * @param array|null $options
     * @return \ZgApp\Model\LogDb
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('Config');
        $model = new \ZgApp\Model\LogDb();
        $model->addWriterPath($sm->get('maindb'), $config['logDb']['table']);
        return $model;
    }

}
