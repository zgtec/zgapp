<?php
/**
 * Copyright (c) 2010-2018. ZGtec,Inc - All Rights Reserved
 * Unauthorized copying of this file, via any medium is strictly prohibited.
 * Proprietary and confidential.
 */

namespace ZgApp\View\Helper\Factory;


use Interop\Container\ContainerInterface;

/**
 *
 * Class ZgApp\View\Helper\Factory\AbstractFactory
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

    protected $module = 'ZgApp';

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
        $className = "\\" . $this->module . "\View\Helper\\" . ucfirst($requestedName);
        $helper = new $className();

        if (method_exists($helper, 'setContainer')) {
            $helper->setContainer($container);
        }

        return $helper;
    }

}